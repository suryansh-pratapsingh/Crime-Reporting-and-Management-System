# 📊 Crime Management System – Statistics Page

This project is an integrated statistics dashboard for the **Crime Management System**, aimed at visualizing crime data for better monitoring and quick decision-making. It uses `Chart.js` to display graphical representations of real-time crime reports directly fetched from the MySQL database.

---

## 🔍 Features

- 🔄 Real-time stats pulled from `crime_reporting_system` database.
- 🟢 **Pie Chart:** Ratio of **Resolved vs Pending** crimes.
- 🌙 **Pie Chart:** Ratio of crimes occurring in **Day vs Night**.
- 📅 Total number of crimes reported:
  - In the **last 24 hours**
  - **Overall**
- ✅ Total **Resolved** crimes count.
- 🕒 Total **Pending** crimes count.
- 🧭 Fully responsive layout matching the `index.php` style (including Navbar, Footer).
- 📈 Charts powered by **Chart.js**.

---

## 📂 Tech Stack

- 💻 HTML, CSS, JavaScript
- 🐘 PHP
- 🗃️ MySQL
- 📊 Chart.js

---

## 🧠 How It Works

1. The statistics page uses the same UI structure and styling as `index.php`.
2. PHP backend connects to the `crime_reporting_system` MySQL database.
3. Data is fetched for various categories:
   - Status-based count (Pending / Resolved)
   - Time-based count (Last 24 hrs / Day vs Night)
4. Chart.js is used to render visual representations using this data.
5. Data is dynamically injected into chart elements using inline PHP.

---

## 📸 Screenshot

![Statistics Dashboard](Screenshot%202025-07-06%20163124.png)

---

## 🖼️ Logo

<img src="image.png" alt="Crime Management System Logo" width="150"/>

---

## 👨‍💻 Developed By

**Suryansh Pratap Singh**  
Under the guidance of **Narender Pal Sir**

---

## 🛠️ Setup Instructions

1. Clone the repository or add the `statistics.php` file to your existing CMS project.
2. Make sure your local server (like XAMPP/WAMP) is running.
3. Ensure your MySQL `crime_reporting_system` database is imported with the schema and data provided.
4. Open the project in browser via `localhost/statistics.php`.

---

## 📦 Dependencies

- Chart.js CDN:  
  Add this to your `<head>`:
  ```html
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
