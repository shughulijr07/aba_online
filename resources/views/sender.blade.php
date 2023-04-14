<form action="/pusher" method="post">
    <input type="text" name="text">
    <input type="submit">
    {{ csrf_field() }}
</form>