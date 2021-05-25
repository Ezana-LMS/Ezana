<?php
/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `ezanaLMS_Settings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
    <a href="" class="brand-link">
        <img src="public/dist/img/<?php echo $sys->logo; ?>" alt="<?php echo $sys->sysname; ?> Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light"><?php echo $sys->sysname; ?></span>
    </a>
<?php
} ?>