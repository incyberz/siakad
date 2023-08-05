<?php 
$username= isset($_GET['username']) ? $_GET['username'] : $username;
?>
<form method="post">
  <table width="100%">
    <tr>
      <td>Username</td>
      <td>
        <input type="text" name="username" class="form-control" required="" minlength="3" maxlength="20" value="<?=$username?>">
      </td>
    </tr>
    <tr>
      <td>Password</td>
      <td>
        <input type="password" name="password" class="form-control" required="" minlength="3" maxlength="20">
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <button class="btn btn-primary" style="margin: 10px 5px 5px 0; border-radius: 15px; padding: 5px 35px" id="btn_login" name="btn_login">Login</button> 
        <a href="mhs/" class="btn btn-success" style="margin: 10px 5px 5px 0;border-radius: 15px;padding: 5px 20px">Login Mahasiswa</a> 
        <?php if($dm) echo " | <a href='akademik/'>Pass Login</a>"; ?>
      </td>
    </tr>
  </table>
</form>
