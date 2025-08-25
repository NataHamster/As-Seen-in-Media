# Mini Media Blog (PHP + MySQL)

A very simple blog-like page and admin panel to showcase press mentions (“As seen in the media”).
Admins can create/edit posts, mark one post as Featured, upload preview images, and link out to the original article and source.

---

## Features

- Minimal admin panel (PHP + jQuery + Bootstrap).
- Create/update posts: title, description, button label, article link.
- Choose a media source from predefined list (media_link table).
- Optional fields: “Media link description”, “Author link”.
- Featured flag for highlighting one post.
- Image upload with client-side preview; .webp guidance for Safari.
- Basic CSRF protection in forms.
- Simple, clean frontend page to display all posts.

---

## Tech Stack

- PHP/MySQL
- HTML/CSS, Bootstrap
- jQuery (for admin-side UI bits)

---

## Project Structure
.
├─ index.php                 # Public frontend (media list)
├─ adminpanel.php            # Admin panel
├─ db.php                    # Database connection
├─ img/
│  └─ media/                 # Uploaded images (gitignored)
│  └─ media-link/            # Uploaded logos (gitignored)
└─ README.md

---

## Database

Tables (minimal example—adjust to your needs):

-- media: posts you show on the site
CREATE TABLE media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  button VARCHAR(100),
  link VARCHAR(512),
  media_link VARCHAR(512),            
  desc_link VARCHAR(255),             
  user_link VARCHAR(255),            
  img VARCHAR(255),                  
  top VARCHAR(10) DEFAULT 'false',    
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;

-- media_link: predefined media sources
CREATE TABLE media_link (
  id INT AUTO_INCREMENT PRIMARY KEY,
  link VARCHAR(512) NOT NULL,         -- canonical source url
  logo VARCHAR(255),                  -- optional logo path
  white TINYINT(1) DEFAULT 0          -- style flag for logo (if needed)
) CHARACTER SET utf8mb4;
