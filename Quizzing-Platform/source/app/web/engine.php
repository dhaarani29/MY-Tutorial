<!DOCTYPE html>
<html lang="en">

<head>
    <title>WK Quiz Engine</title>
</head>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<body>
<form action="" enctype='application/json' method="post" target="engine" name ="f1">
<table width="100%">
    <tr><td> Engine URL: </td><td><input type="text" id="engineURL" name="engineURL" size="100px;"/></td></tr>
    <tr><td>Access Token: </td><td><input type="text" name="accessToken" size="100px;"/></td></tr>
    <tr><td>&nbsp;</td><td><input type="button" value="Start Engine" onclick="javascript:startEngine()"/></td></tr>
</table> 
</form>

<script language="javascript">
    function startEngine() {
        document.f1.action    =    document.getElementById("engineURL").value
        document.f1.submit();
    }
</script>

<!-- when the form is submitted, the server response will appear in this iframe -->
<iframe name="engine" src="" width="100%" height="800px;"></iframe></body>

</html>
<!-- <script type="text/javascript">
$.ajax({
    type: "GET",
    url: "http://localhost/enduser/engine/58/1",
    contentType: "application/x-www-form-urlencoded",
    beforeSend: function(xhr, settings) {
        xhr.setRequestHeader("token", "Bearer%20eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjY3LCJjbGllbnRVc2VySWQiOjIyLCJjbGllbnRJZCI6MiwidXNlclR5cGVOYW1lIjoiRVVQIiwiZmlyc3ROYW1lIjoiamF1cF9maXJzdG5hbWUiLCJtaWRkbGVOYW1lIjpudWxsLCJsYXN0TmFtZSI6ImphdXBkX2xhc3RuYW1lIiwiZXhwIjoxNDkyMjUwNzI4fQ.H4ixl6okmzo_61yV3DReWARIgakuOrHCcjjVnlVuQks");
    },
    success: function(data) {
        console.log(data)
        //$("#reader").attr('src',"/");
        //$("#output_iframe_id").contents().html(data);                 
        $("#reader").attr('src', "data:text/html;charset=utf-8," + encodeURI(data))
    }
});
</script>

 -->