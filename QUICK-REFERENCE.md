# Quick Reference Card - NephSpace Elite Construction Website

## 🎯 Essential Information

### Website URL (Local)
```
http://localhost/NephSpace Elite Construction/index.html
```

### Main Files
- `index.html` - Homepage
- `about.html` - About page
- `contact.php` - Contact form handler
- `css/style.css` - All styles
- `js/main.js` - All JavaScript

## 🎨 Color Codes

```css
Primary (Gold):     #d4a574
Secondary (Dark):   #2c3e50
Accent (Light Gold): #c89860
Dark:               #1a1a1a
Light:              #f8f9fa
Text:               #333333
```

## 📧 Contact Information to Update

**In index.html and about.html:**
- Line search: `+254 700 000 000` → Your phone
- Line search: `info@nephspace.co.ke` → Your email
- Line search: `Nairobi, Kenya` → Your address

**In contact.php:**
- Line 60: `$to = 'info@nephspace.co.ke';` → Your email

## 📸 Required Images

Place in `assets/img/` folder:

| File Name | Size | Description |
|-----------|------|-------------|
| hero1.jpg | 1920x1080 | Main hero image |
| hero2.jpg | 1920x1080 | Second slider |
| hero3.jpg | 1920x1080 | Third slider |
| about.jpg | 800x600 | About section |
| project1.jpg | 600x400 | Office Complex |
| project2.jpg | 600x400 | Luxury Villa |
| project3.jpg | 600x400 | Corporate Interior |
| project4.jpg | 600x400 | Shopping Mall |
| project5.jpg | 600x400 | Apartment Complex |
| project6.jpg | 600x400 | Hotel Development |
| client1.jpg | 100x100 | Client photo |
| client2.jpg | 100x100 | Client photo |

## 🔧 Common Customizations

### Change Primary Color
`css/style.css` line 8:
```css
--primary-color: #d4a574;
```

### Change Company Name
Search and replace: `NephSpace` with your name

### Change Tagline
Search and replace: `Building Excellence, Defining Spaces`

### Add Social Media Links
Search for: `href="#"` in footer sections

## 🚀 Testing Checklist

- [ ] Open in Chrome
- [ ] Open in Firefox
- [ ] Test on mobile (resize browser)
- [ ] Submit contact form
- [ ] Check all navigation links
- [ ] Test hero slider
- [ ] Test project filter
- [ ] Scroll to top button works

## 📱 Responsive Breakpoints

- Desktop: 992px and above
- Tablet: 768px - 991px
- Mobile: Below 768px

## 🔗 External Libraries Used

- Bootstrap 5.3.2
- Font Awesome 6.4.2
- AOS (Animate On Scroll)
- Lightbox2
- Google Fonts (Poppins, Playfair Display)

## 📝 Key Sections in index.html

1. Navigation (Line 27)
2. Hero Slider (Line 49)
3. About (Line 91)
4. Services (Line 144)
5. Stats (Line 289)
6. Projects (Line 289)
7. Testimonials (Line 433)
8. Contact (Line 457)
9. Footer (Line 583)

## 🎯 Important Notes

1. **Images:** Website works without images but shows placeholders
2. **Email:** Contact form needs PHP server to work
3. **XAMPP:** Must start Apache for PHP to work
4. **Mobile:** Test on real devices, not just browser resize
5. **SEO:** Update meta descriptions in each HTML file

## 🆘 Troubleshooting

**Contact form not working?**
→ Check if Apache is running in XAMPP

**Images not showing?**
→ Check file names match exactly (case-sensitive)

**Layout broken?**
→ Clear browser cache (Ctrl+F5)

**Mobile menu not closing?**
→ Check Bootstrap JS is loaded

## 📞 Support Files

- `README.md` - Full documentation
- `SETUP-GUIDE.md` - Setup instructions
- `PROJECT-SUMMARY.md` - Project overview
- `database-schema.sql` - Database setup (optional)

## ✅ Pre-Launch Checklist

- [ ] All images added
- [ ] Contact info updated
- [ ] Email configured in contact.php
- [ ] Social media links added
- [ ] Tested on multiple browsers
- [ ] Tested on mobile devices
- [ ] Contact form tested
- [ ] All links working
- [ ] Meta descriptions updated
- [ ] Favicon added (optional)

## 🌐 Deployment

1. Upload all files via FTP/cPanel
2. Ensure folder structure is maintained
3. Test contact form on live server
4. Check all images load
5. Test on different devices

---

**Quick Help:** If stuck, check SETUP-GUIDE.md for detailed instructions!

