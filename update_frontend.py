import re

with open('resources/js/Pages/Admin/Certificates/Index.jsx', 'r', encoding='utf-8') as f:
    content = f.read()

new_btn = """{row.has_certificate && (
                            <button
                              type="button"
                              onClick={() => handleDelete(row.certificate_id || row.id)}"""

content = re.sub(r'\{row\.certificate_id && \(\s*<button\s*type="button"\s*onClick=\{\(\) => handleDelete\(row\.certificate_id\)\}', new_btn, content)

with open('resources/js/Pages/Admin/Certificates/Index.jsx', 'w', encoding='utf-8') as f:
    f.write(content)
