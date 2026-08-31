import re

with open('app/Http/Controllers/CertificateController.php', 'r', encoding='utf-8') as f:
    content = f.read()

new_destroy = """    public function destroy(Request $request, $id)
    {
        if (str_starts_with($id, 'peserta_') || str_starts_with($id, 'pembicara_')) {
            $parts = explode('_', $id);
            $role = $parts[0];
            $foreignId = $parts[1];

            if ($role === 'peserta') {
                $reg = \App\Models\EventRegistration::find($foreignId);
                if ($reg && $reg->certificate_path) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($reg->certificate_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($reg->certificate_path);
                    }
                    $reg->update(['certificate_path' => null]);
                    
                    $cert = \App\Models\Certificate::where('event_id', $reg->bimtek_event_id)->where('user_id', $reg->user_id)->first();
                    if ($cert) {
                        if ($cert->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($cert->file_path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($cert->file_path);
                        }
                        $cert->delete();
                    }
                }
            } else {
                $es = \App\Models\EventSpeaker::find($foreignId);
                if ($es && $es->certificate_path) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($es->certificate_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($es->certificate_path);
                    }
                    $es->update(['certificate_path' => null]);
                    
                    $cert = \App\Models\Certificate::where('event_id', $es->bimtek_event_id)->where('role_type', 'pembicara')->first(); // approximation
                    if ($cert) {
                        if ($cert->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($cert->file_path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($cert->file_path);
                        }
                        $cert->delete();
                    }
                }
            }
        } else {
            $cert = \App\Models\Certificate::findOrFail($id);
            if ($cert->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($cert->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cert->file_path);
            }
            
            // clear from registration/speaker as well
            if ($cert->role_type === 'peserta') {
                \App\Models\EventRegistration::where('bimtek_event_id', $cert->event_id)
                    ->where('user_id', $cert->user_id)
                    ->update(['certificate_path' => null]);
            } else {
                \App\Models\EventSpeaker::where('bimtek_event_id', $cert->event_id)
                    ->update(['certificate_path' => null]); // approximation
            }
            
            $cert->delete();
        }

        return back()->with('success', 'Sertifikat berhasil dihapus dari repository.');
    }"""

content = re.sub(r'public function destroy\(\$id\)\s*\{.*?\n        return back\(\)->with\(\'success\', \'Sertifikat berhasil dihapus dari repository\.\'\);\n    \}', new_destroy, content, flags=re.DOTALL)

with open('app/Http/Controllers/CertificateController.php', 'w', encoding='utf-8') as f:
    f.write(content)
