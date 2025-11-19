<!DOCTYPE html>
<html>
<head>
<title>St. Benedict's Academy</title>
<style>
body {
font-family: 'Segoe UI', sans-serif;
margin: 0;
background: #f4f4f9;
}
header {
background-color: #003366;
color: white;
padding: 30px 0;
text-align: center;
}
nav {
background: #0059b3;
text-align: center;
padding: 10px;
}
nav a {
color: white;
text-decoration: none;
margin: 0 15px;
font-weight: bold;
}
nav a:hover {
text-decoration: underline;
}
.container {
display: flex;
justify-content: center;
gap: 30px;
margin: 40px auto;
max-width: 900px;
}
.card {
background: white;
padding: 20px;
border-radius: 8px;
box-shadow: 0 0 10px #ccc;
width: 45%;
transition: transform 0.3s;
}
.card:hover {
transform: scale(1.05);
}
.card h2 {
color: #003366;
}
footer {
background: #003366;
color: white;
text-align: center;
padding: 15px 0;
margin-top: 50px;
}
</style>
</head>
<body>
<header>
<h1>St. Benedict's Academy</h1>
<p>Your Future Begins Here</p>
</header>
<nav>
<a href="#pu">PU College</a>
<a href="#degree">Degree College</a>
</nav>
<div class="container">
<div class="card" id="pu">
<h2>PU College</h2>
<p>Streams Offered:</p>
<ul>
<li>Science (PCMB, PCMC)</li>
<li>Commerce (CEBA, SEBA, HEBA)</li>
<li>Arts</li>
</ul>
</div>
<div class="card" id="degree">
<h2>Degree College</h2>
<p>Courses Offered:</p>
<ul>
<li>BCA – Computer Applications</li>
<li>BCom – Commerce & Finance</li>
<li>BA – Arts & Humanities</li>
</ul>
</div>
</div>
<footer>
&copy; <?php echo date("Y"); ?> St. Benedict's Academy. All rights reserved.
</footer>
</body>
</html>