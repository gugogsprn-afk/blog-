<form method="POST" action="?" id="popup-form" class="windowform" >
    <input type="hidden" name="show" value="adminmembers" />
    <input type="hidden" name="voider" value="passform" />
    <input type="hidden" name="response" value="json" />
    <table class="fieldset">
        <tr>
            <th style="width: 150px;">Current password</th>
            <td><input type="password" id="member-pass" name="pass" class="k-input" style="width: 200px;" /></td>
        </tr>
        <tr>
            <th style="width: 150px;">New password</th>
            <td><input type="password" id="member-passa" name="passa" class="k-input" style="width: 200px;" /></td>
        </tr>
        <tr>
            <th style="width: 150px;">Confirm password</th>
            <td><input type="password" id="member-passb" name="passb" class="k-input"  style="width: 200px;"/></td>
        </tr>
        <tr class="notthis">
            <td colspan="2" class="submitrow">
                <input type="submit" class="k-button submit" value="Save" />
            </td>
        </tr>
    </table>
</form>