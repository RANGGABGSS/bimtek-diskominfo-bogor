import os

def fix_border(filename):
    filepath = os.path.join(r"C:\Users\User\.gemini\antigravity\scratch\bimtek-diskominfo-bogor\resources\js\Pages\Admin\Reports", filename)
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    old_str = 'className="bg-white p-6 sm:p-10 rounded-3xl border-2 border-slate-400 shadow-2xl mx-auto space-y-4 text-slate-900 font-sans print:shadow-none print:border-none print:p-0 print:m-0 print:w-full"'
    new_str = 'className="bg-white p-6 sm:p-10 mx-auto space-y-4 text-slate-900 font-sans print:p-0 print:m-0 print:w-full"'
    
    content = content.replace(old_str, new_str)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_border('ParticipantsReport.jsx')
fix_border('HonorariumReport.jsx')
