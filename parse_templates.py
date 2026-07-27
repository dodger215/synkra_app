#!/usr/bin/env python3
import os, json, re

BASE = "/home/ked/Documents/dummy_e-commerces"
OUT  = "/home/ked/Documents/business/flowexa/storage/app/ecommerce_templates.json"

TEMPLATE_META = {
    'boutique-fashion-ecommerce':      {'name':'Boutique Fashion','preview':'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=240&fit=crop','desc':'Elegant editorial fashion with serif typography'},
    'cyberpunk-sneakers-ecommerce':    {'name':'Cyberpunk Sneakers','preview':'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=400&h=240&fit=crop','desc':'Dark neon cyberpunk sneaker store'},
    'fashion-ecommerce':               {'name':'Modern Fashion','preview':'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=400&h=240&fit=crop','desc':'Clean modern fashion layout'},
    'luxury-ecommerce':                {'name':'Luxury Maison','preview':'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=400&h=240&fit=crop','desc':'Premium gold-accented luxury experience'},
    'minimalist-ecommerce':            {'name':'Minimalist Scandi','preview':'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=400&h=240&fit=crop','desc':'Scandinavian minimalist white-space design'},
    'neumorphism-tech-ecommerce':      {'name':'Neumorphic Tech','preview':'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=240&fit=crop','desc':'Soft-UI neumorphic tech store'},
    'outdoor-adventure-ecommerce':     {'name':'Outdoor Adventure','preview':'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=400&h=240&fit=crop','desc':'Bold dark outdoor gear store'},
    'pastel-lifestyle-ecommerce':      {'name':'Pastel Lifestyle','preview':'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=240&fit=crop','desc':'Soft pastel lifestyle store'},
    'seesail-inspired-ecommerce':      {'name':'Seesail Blue','preview':'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=240&fit=crop','desc':'Clean blue nautical storefront'},
    'structured-marketplace-ecommerce':{'name':'Structured Marketplace','preview':'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=400&h=240&fit=crop','desc':'Multi-category marketplace layout'},
    'vibrant-gadgets-ecommerce':       {'name':'Vibrant Gadgets','preview':'https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=400&h=240&fit=crop','desc':'Vibrant colorful gadgets store'},
}

def read(path):
    if not os.path.exists(path): return ''
    with open(path,'r',encoding='utf-8',errors='ignore') as f: return f.read()

def extract_css_vars(css):
    m = re.search(r':root\s*\{([^}]+)\}', css, re.DOTALL)
    if not m: return {}
    return {k.strip(): v.strip() for k,v in re.findall(r'(--[\w-]+)\s*:\s*([^;]+);', m.group(1))}

def extract_font_url(css):
    m = re.search(r"@import url\(['\"]?(https://fonts\.googleapis\.com/[^'\")\s]+)", css)
    return m.group(1) if m else ''

def clean_css(css):
    # Remove tailwind @import and @layer blocks
    css = re.sub(r"@import ['\"]tailwindcss[^'\"]*['\"];?\s*\n", '', css)
    result, depth, in_layer = [], 0, False
    for line in css.split('\n'):
        if re.match(r'\s*@layer\s+', line): in_layer = True
        if in_layer:
            depth += line.count('{') - line.count('}')
            if depth <= 0: in_layer, depth = False, 0
            continue
        result.append(line)
    return '\n'.join(result)

def extract_top_sections(body):
    """Extract top-level nav/section/header/footer/main blocks."""
    sections = []
    tags = ['nav','header','section','main','footer']
    i = 0
    body = body.strip()
    while i < len(body):
        m = None
        for tag in tags:
            m = re.match(r'\s*(<' + tag + r'(\s[^>]*)?>)', body[i:], re.IGNORECASE)
            if m:
                found_tag = tag
                break
        if m:
            start = i
            i += len(m.group(0))
            depth = 1
            while i < len(body) and depth > 0:
                o = re.search(r'<' + found_tag + r'[\s>]', body[i:], re.IGNORECASE)
                c = re.search(r'</' + found_tag + r'>', body[i:], re.IGNORECASE)
                if o and (not c or o.start() < c.start()):
                    depth += 1; i += o.end()
                elif c:
                    depth -= 1; i += c.end()
                else:
                    break
            full = body[start:i].strip()
            if full:
                sections.append({'tag': found_tag, 'html': full})
        else:
            i += 1
    return sections

def logo_from_nav(html):
    for pat in [r'class="[^"]*(?:logo|brand|nav-logo)[^"]*"[^>]*>\s*([^<]{1,40})\s*<',
                r'<a[^>]*href="[./]*"[^>]*>\s*([^<]{1,40})\s*<']:
        m = re.search(pat, html, re.IGNORECASE)
        if m:
            t = re.sub(r'<[^>]+>','',m.group(1)).strip()
            if t: return t
    return 'Store'

def footer_colors(html, vars):
    bg, fg = '#1e293b','#ffffff'
    sm = re.search(r'style="([^"]+)"', html)
    if sm:
        s = sm.group(1)
        bm = re.search(r'background(?:-color)?\s*:\s*([^;]+)', s)
        if bm:
            v = bm.group(1).strip()
            bg = vars.get(re.search(r'var\((--[\w-]+)',v).group(1),bg) if 'var(' in v else v
        cm = re.search(r'\bcolor\s*:\s*([^;]+)', s)
        if cm:
            v = cm.group(1).strip()
            fg = vars.get(re.search(r'var\((--[\w-]+)',v).group(1),fg) if 'var(' in v else v
    return bg, fg

def clean_section(html):
    html = re.sub(r'<script[^>]*>.*?</script>','', html, flags=re.DOTALL|re.IGNORECASE)
    html = re.sub(r'\s*data-aos(?:-[\w]+)*="[^"]*"','', html)
    html = re.sub(r'\s*data-aos="[^"]*"','', html)
    return html.strip()

def sections_to_elements(sections, vars, page_key, store_name='Store'):
    elements, footer, y = [], None, 0
    for i, sec in enumerate(sections):
        tag, html = sec['tag'], sec['html']
        low = html.lower()

        if tag == 'nav':
            logo = logo_from_nav(html)
            bg = vars.get('--bg', vars.get('--cream', vars.get('--surface','#ffffff')))
            color = vars.get('--text', vars.get('--dark','#1e293b'))
            elements.append({'id':f'tpl_nav_{i}','type':'navbar','content':{'logo':logo,'links':['Products','Categories','Contact'],'style':'modern'},'styles':{'left':0,'top':0,'width':'100%','padding':'20px 60px','backgroundColor':bg,'color':color,'zIndex':10}})
            y = 80

        elif tag == 'footer':
            bg, fg = footer_colors(html, vars)
            footer = {'template':'standard','styles':{'backgroundColor':bg,'color':fg,'padding':'60px 40px','fontSize':'14px','minHeight':'240px'},'content':{'aboutTitle':'About Us','aboutText':'Premium products curated for exceptional quality.','copyright':f'© 2025 {store_name}. All rights reserved.','links':[{'label':'Privacy Policy','url':'#'},{'label':'Terms of Service','url':'#'},{'label':'Shipping','url':'#'},{'label':'Returns','url':'#'}]}}

        else:
            is_product = bool(re.search(r'product|grid|collection',low))
            is_cat = bool(re.search(r'categor|cat-scroll',low))

            if is_product and not is_cat:
                elements.append({'id':f'tpl_grid_{i}','type':'product_grid','content':{'title':'Featured Products','limit':4,'columns':4},'styles':{'left':0,'top':y,'width':'100%','padding':'40px 60px'}})
                y += 420
            elif is_cat:
                elements.append({'id':f'tpl_cats_{i}','type':'category_list','content':{},'styles':{'left':0,'top':y,'width':'100%','padding':'16px 60px'}})
                y += 80
            else:
                clean = clean_section(html)
                if len(clean) < 30: continue
                elements.append({'id':f'tpl_block_{i}','type':'html_block','content':clean,'styles':{'left':0,'top':y,'width':'100%','padding':'0','backgroundColor':'transparent'}})
                y += max(180, min(500, len(clean)//4))

    return elements, footer

PAGE_MAP = {
    'index.html':    ('home','Home',['main.js','store.js']),
    'products.html': ('products','Products',['products.js','store.js']),
    'product.html':  ('product_detail','Product Detail',['product.js','store.js']),
    'cart.html':     ('cart','Cart',['cart.js','store.js']),
}

templates = []
for folder in sorted(os.listdir(BASE)):
    fp = os.path.join(BASE, folder)
    if not os.path.isdir(fp) or folder.startswith('.'): continue
    meta = TEMPLATE_META.get(folder, {'name':folder.replace('-',' ').title(),'preview':'','desc':''})

    raw_css = read(os.path.join(fp,'style.css'))
    css_vars = extract_css_vars(raw_css)
    clean = clean_css(raw_css)
    font_url = extract_font_url(raw_css)

    # Build theme CSS string (without tailwind, with vars resolved)
    theme_css = f"@import url('{font_url}');\n{clean}" if font_url else clean

    tdata = {'id':folder,'name':meta['name'],'preview':meta['preview'],'desc':meta['desc'],'pages':{}}

    for html_file,(page_key,page_name,_) in PAGE_MAP.items():
        html = read(os.path.join(fp, html_file))
        if not html: continue

        body_m = re.search(r'<body[^>]*>(.*?)</body>', html, re.DOTALL|re.IGNORECASE)
        body = body_m.group(1) if body_m else html

        sections = extract_top_sections(body)
        elements, footer = sections_to_elements(sections, css_vars, page_key, meta['name'])

        if not footer:
            footer = {'template':'standard','styles':{'backgroundColor':'#1e293b','color':'#ffffff','padding':'60px 40px','fontSize':'14px','minHeight':'240px'},'content':{'aboutTitle':f'About {meta["name"]}','aboutText':'We bring you the best curated products.','copyright':f'© 2025 {meta["name"]}. All rights reserved.','links':[{'label':'Privacy','url':'#'},{'label':'Terms','url':'#'},{'label':'Returns','url':'#'}]}}

        tdata['pages'][page_key] = {
            'name': page_name,
            'theme_css': theme_css,
            'elements': elements,
            'footer': footer,
        }

    if tdata['pages']:
        templates.append(tdata)
        pg = len(tdata['pages'])
        el_count = sum(len(p['elements']) for p in tdata['pages'].values())
        print(f"✓ {meta['name']:35s} {pg} pages, {el_count} elements")

with open(OUT,'w',encoding='utf-8') as f:
    json.dump(templates, f, indent=2)

size_kb = os.path.getsize(OUT)//1024
print(f"\nDone! {len(templates)} templates → {OUT} ({size_kb} KB)")
