import os

def fix_imports(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    if 'import ResizableImage' not in content:
        content = content.replace(
            "import { Head, Link } from '@inertiajs/react';",
            "import { Head, Link } from '@inertiajs/react';\nimport ResizableImage from '@/Components/ResizableImage';"
        )
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
            
    # Also fix the image tags if they haven't been replaced yet
    if '<img' in content and 'logo_diskominfo_bogorkab.png' in content:
        img_tag = """<img 
                  src="/images/logo_diskominfo_bogorkab.png" 
                  alt="Logo Diskominfo Kabupaten Bogor" 
                  className="h-20 sm:h-24 object-contain shrink-0" 
                />"""
        new_img_tag = """<ResizableImage 
                  src="/images/logo_diskominfo_bogorkab.png" 
                  alt="Logo Diskominfo Kabupaten Bogor" 
                  initialWidth={260} 
                  isEditable={isEditable} 
                />"""
        if img_tag in content:
            content = content.replace(img_tag, new_img_tag)
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(content)


fix_imports('resources/js/Pages/Admin/Reports/SpeakersReport.jsx')
fix_imports('resources/js/Pages/Admin/Reports/ParticipantsReport.jsx')
fix_imports('resources/js/Pages/Admin/Reports/HonorariumReport.jsx')
