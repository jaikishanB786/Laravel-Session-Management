<!DOCTYPE html>
<html>
<head>
    <title>Session Prompt</title>
</head>
<body>
    <h1>Session Prompt</h1>
    <p>A session is already in progress. What would you like to do?</p>
    <form method="post" action="/session/close">
        @csrf
        <button type="submit">Close Previous Session</button>
    </form>
</body>
</html>
