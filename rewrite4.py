import re

def update_speakers_report():
    filepath = r"C:\Users\User\.gemini\antigravity\scratch\bimtek-diskominfo-bogor\resources\js\Pages\Admin\Reports\SpeakersReport.jsx"
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Add import
    if "ResizableImage" not in content:
        content = content.replace("import LiveConnectionBadge from '@/Components/LiveConnectionBadge';", 
                                  "import LiveConnectionBadge from '@/Components/LiveConnectionBadge';\nimport ResizableImage from '@/Components/ResizableImage';")

    # Replace img with ResizableImage
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
                
    content = content.replace(img_tag, new_img_tag)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

if __name__ == '__main__':
    update_speakers_report()
