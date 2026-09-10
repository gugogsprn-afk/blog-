<form id="dummyForm" method="get" action="index.php" >
    {{for reload as param}}
    <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
    {{end for reload}}
</form>
<script >
    var frm = document.forms["dummyForm"];
     frm.submit();
</script>