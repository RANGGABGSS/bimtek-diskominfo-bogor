<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BimtekEvent;
use App\Models\FormField;
use Inertia\Inertia;

class FormBuilderController extends Controller
{
    public function edit($eventId)
    {
        $event = BimtekEvent::with('formFields')->findOrFail($eventId);

        return Inertia::render('Admin/FormBuilder', [
            'event' => $event,
            'formFields' => $event->formFields,
        ]);
    }

    public function store(Request $request, $eventId)
    {
        $event = BimtekEvent::findOrFail($eventId);

        $validated = $request->validate([
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,select,radio,checkbox,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
        ]);

        $maxOrder = $event->formFields()->max('order_index') ?? 0;

        $event->formFields()->create([
            'field_label' => $validated['field_label'],
            'field_type' => $validated['field_type'],
            'field_options' => $validated['field_options'] ?? null,
            'is_required' => $validated['is_required'] ?? true,
            'order_index' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Field form berhasil ditambahkan!');
    }

    public function update(Request $request, $fieldId)
    {
        $field = FormField::findOrFail($fieldId);

        $validated = $request->validate([
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,select,radio,checkbox,file,date',
            'field_options' => 'nullable|array',
            'is_required' => 'boolean',
        ]);

        $field->update($validated);

        return back()->with('success', 'Field form berhasil diperbarui!');
    }

    public function reorder(Request $request, $eventId)
    {
        $request->validate([
            'ordered_ids' => 'required|array',
            'ordered_ids.*' => 'exists:form_fields,id',
        ]);

        foreach ($request->ordered_ids as $index => $id) {
            FormField::where('id', $id)->update(['order_index' => $index + 1]);
        }

        return back()->with('success', 'Urutan field berhasil disimpan!');
    }

    public function destroy($fieldId)
    {
        $field = FormField::findOrFail($fieldId);
        $field->delete();

        return back()->with('success', 'Field form berhasil dihapus.');
    }
}
