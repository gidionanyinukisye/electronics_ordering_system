/* ===============================
   ADMIN PANEL STYLESHEET
   Author: Mwakyembe Gidion
   Modern & Professional
================================= */

/* ----- General Styles ----- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
    color: #1f2937;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

/* ----- Headings ----- */
h1,h2,h3,h4 {
    color: #111827;
    margin-bottom: 15px;
}

/* ----- Tables ----- */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 14px;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

table th {
    background-color: #f3f4f6;
    color: #374151;
    font-weight: 600;
}

table tr:hover {
    background-color: #f9fafb;
    transition: 0.2s;
}

/* ----- Buttons ----- */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary { background-color: #3b82f6; color: #fff; }
.btn-primary:hover { background-color: #2563eb; }

.btn-warning { background-color: #f59e0b; color: #fff; }
.btn-warning:hover { background-color: #d97706; }

.btn-danger { background-color: #ef4444; color: #fff; }
.btn-danger:hover { background-color: #b91c1c; }

/* ----- Badges ----- */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

/* User Status */
.active { background:#dcfce7; color:#166534; }
.blocked { background:#fee2e2; color:#991b1b; }

/* Order Status */
.pending { background:#fef3c7; color:#92400e; }
.approved { background:#dcfce7; color:#166534; }
.shipped { background:#dbeafe; color:#1e40af; }
.delivered { background:#e0f2fe; color:#0c4a6e; }
.cancelled { background:#fee2e2; color:#991b1b; }

/* ----- Forms ----- */
input[type="text"], input[type="email"], input[type="number"], select {
    width: 100%;
    padding: 8px 12px;
    margin: 5px 0 15px 0;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

button, select {
    cursor: pointer;
}

/* ----- Layout & Misc ----- */
hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 20px 0;
}

p {
    margin: 5px 0;
}

/* ----- Responsive ----- */
@media screen and (max-width: 768px){
    .container { padding: 15px; }
    table th, table td { padding: 10px; }
    .btn { padding: 5px 10px; font-size: 12px; }
    .badge { font-size: 11px; padding: 3px 8px; }
}