<?php
/* Persisit System Settings On Footer */
$ret = "SELECT * FROM `ezanaLMS_Settings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
    <footer class="main-footer">
        Copyright &copy; 2020 - <?php echo date('Y'); ?> <?php echo $sys->sysname; ?>.
        Powered By <a target="_blank" href="https://ezana.org">EzanaLMS ORG</a>.
        A <a target="_blank" href="https://martdev.info">MartDevelopers Inc </a> Production.
        All Rights Reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> <?php echo $sys->version; ?>
        </div>
    </footer>
<?php
} ?>