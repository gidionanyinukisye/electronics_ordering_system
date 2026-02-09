body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, sans-serif;
  background: #f4f6f9;
}

.sidebar {
  width: 230px;
  height: 100vh;
  background: #111827;
  position: fixed;
  color: white;
  padding: 20px;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
}

.sidebar a {
  display: block;
  color: #cbd5e1;
  padding: 12px;
  text-decoration: none;
  border-radius: 6px;
  margin-bottom: 8px;
}

.sidebar a:hover {
  background: #1f2933;
  color: white;
}

.main-content {
  margin-left: 250px;
  padding: 30px;
}

h1 {
  margin-bottom: 20px;
  color: #111827;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}

.card h3 {
  margin: 0;
  color: #6b7280;
}

.card p {
  font-size: 32px;
  font-weight: bold;
  margin-top: 10px;
  color: #111827;
}

.card.warning {
  border-left: 6px solid #f59e0b;
}