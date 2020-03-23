<!DOCTYPE html>
<html>
<head>
    <title>ברוך הבא ל BetterBuilding</title>
</head>

<body>
<h2>ברוך הבא {{$user['name']}}</h2>
<br/>
אתה נרשמתה עם האימייל: {{$user['email']}}
<br/>
הסיסמה שלך היא: {{$password}}
<br/>
קישור למערכת:
<a href="{{url('/')}}">כניסה לאתר</a>
</body>

</html>