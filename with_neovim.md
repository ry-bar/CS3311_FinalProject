# 🧰 Local Dev Setup (XAMPP + Neovim)

> 🖥️ **Note:** These setup instructions were written and tested on **macOS** using **XAMPP** and **Neovim**.  
> Windows users can follow the same steps, but some paths and commands differ — see the **Windows Notes** section at the end.


These instructions get the project running locally on macOS with **XAMPP** and **Neovim**, using the same configuration Ryan used.  
You’ll only need to do this **once per machine**.

---

## 1️⃣ Install & Open XAMPP

1. Download XAMPP for macOS from [apachefriends.org](https://www.apachefriends.org/index.html).  
2. Install and start the **Apache** server (MySQL optional).

---

## 2️⃣ Set Apache’s Document Root

Edit XAMPP’s main config file:

```bash
sudo nano /Applications/XAMPP/xamppfiles/etc/httpd.conf
```

Find these two lines:

```apache
DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs"
<Directory "/Applications/XAMPP/xamppfiles/htdocs">
```

Replace them with your **local project path**, for example:

```apache
DocumentRoot "/Users/<your_username>/dev/school/cs3311/sites"
<Directory "/Users/<your_username>/dev/school/cs3311/sites">
    Options Indexes FollowSymLinks ExecCGI Includes
    AllowOverride All
    Require all granted
</Directory>

DirectoryIndex index.php index.html
```

> 💡 **Tip:** Replace `<your_username>` with your macOS username.

Save and exit the file (`Ctrl+O`, `Enter`, `Ctrl+X`).

---

## 3️⃣ Give Apache Permission to Read Your Project

Run these commands in Terminal (update `<your_username>` if needed):

```bash
sudo chmod +a "daemon allow execute,list,search" /Users/<your_username>
sudo chmod +a "daemon allow execute,list,search" /Users/<your_username>/dev
sudo chmod +a "daemon allow execute,list,search" /Users/<your_username>/dev/school
sudo chmod +a "daemon allow execute,list,search" /Users/<your_username>/dev/school/cs3311
sudo chmod +a "daemon allow execute,list,search" /Users/<your_username>/dev/school/cs3311/sites
sudo chmod -R +a "daemon allow read,execute,list,search,file_inherit,directory_inherit" \
  /Users/<your_username>/dev/school/cs3311/sites
```

These allow Apache’s background user (`daemon`) to access your project directories safely.

---

## 4️⃣ Restart Apache

```bash
sudo /Applications/XAMPP/xamppfiles/xampp restartapache
```

Or restart from the **XAMPP Control Panel → Apache → Stop → Start**.

---

## 5️⃣ Test That It Works

Create a quick PHP test page:

```bash
cat > ~/dev/school/cs3311/sites/index.php <<'PHP'
<?php phpinfo();
PHP
```

Then visit [http://localhost/](http://localhost/) in your browser.  
You should see the PHP info page.

Once confirmed, replace it with the project files (e.g., `CS3311_FinalProject`).

---

## 6️⃣ Clone & Run the Project

```bash
cd ~/dev/school/cs3311/sites
git clone https://github.com/<your-org>/<your-repo>.git CS3311_FinalProject
```

Then open the site in your browser:

👉 [http://localhost/CS3311_FinalProject/](http://localhost/CS3311_FinalProject/)

---

## 7️⃣ Editing with Neovim

Open the project in Neovim:

```bash
cd ~/dev/school/cs3311/sites/CS3311_FinalProject
nvim .
```

When you save files, just refresh your browser to see updates.

Optional: automatic live-reload while coding (requires Node.js):

```bash
npm install -g browser-sync
browser-sync start --proxy "localhost/CS3311_FinalProject" --files "**/*"
```

Then open [http://localhost:3000](http://localhost:3000) for live refreshes.

---

## ✅ Troubleshooting

| Symptom | Fix |
|----------|-----|
| **Access forbidden (403)** | Permissions (step 3) didn’t apply; rerun the chmod commands. |
| **404 Not Found** | Wrong `DocumentRoot` or files not in the right folder. |
| **CSS/JS missing** | Check that links are **relative** (no leading `/`). |
| **Apache won’t start** | Port 80 already in use — stop other web servers or change the `Listen` port in `httpd.conf`. |

---

## 🧩 Optional: Windows Notes

If a teammate uses XAMPP on Windows:
- XAMPP installs in `C:\xampp\`.
- Edit `C:\xampp\apache\conf\httpd.conf` the same way.
- Use paths like:
  ```
  DocumentRoot "C:/Users/<username>/dev/school/cs3311/sites"
  <Directory "C:/Users/<username>/dev/school/cs3311/sites">
  ```
- No need for `chmod` commands on Windows.

---

### 🎉 Done!

Visit **[http://localhost/CS3311_FinalProject/](http://localhost/CS3311_FinalProject/)** and start coding!
