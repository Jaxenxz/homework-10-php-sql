<?php
require("dbconnect.php");

$sql = "SELECT * FROM employee ORDER BY eType ASC;";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("การค้นหาข้อมูลผิดพลาด: " . mysqli_error($con));
}

function convertDateToThai($date)
{
    $thaiMonths = [
        1 => "มกราคม",
        2 => "กุมภาพันธ์",
        3 => "มีนาคม",
        4 => "เมษายน",
        5 => "พฤษภาคม",
        6 => "มิถุนายน",
        7 => "กรกฎาคม",
        8 => "สิงหาคม",
        9 => "กันยายน",
        10 => "ตุลาคม", 
        11 => "พฤศจิกายน",
        12 => "ธันวาคม"
    ];
    $dateParts = explode('-', $date); 
    $year = (int)$dateParts[0] + 543;
    $month = (int)$dateParts[1]; 
    $day = (int)$dateParts[2];

    return "{$day} {$thaiMonths[$month]} {$year}";
}

function calAge($date)
{
    date_default_timezone_set('Asia/Bangkok');
    $curDate = date('Y-m-d'); 
    $dateParts = explode('-', $date);
    $birthyear = (int)$dateParts[0];
    $curdateParts = explode('-', $curDate);
    $curyear = (int)$curdateParts[0];
    return ($curyear - $birthyear);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการข้อมูลพนักงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <div class="header-actions">
                    <h3 class="header-title">
                        <i class="fas fa-users me-2"></i>ข้อมูลพนักงาน
                    </h3>
                    <div class="input-group header-search">
                        <input type="text" class="form-control" placeholder="ค้นหาพนักงาน..." aria-label="ค้นหาพนักงาน">
                        <button class="btn btn-outline-light" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">รหัสพนักงาน</th>
                            <th style="width: 8%;">คำนำหน้า</th>
                            <th style="width: 17%;">ชื่อ-นามสกุล</th>
                            <th style="width: 5%;">อายุ</th>
                            <th style="width: 15%;">สังกัด</th>
                            <th style="width: 35%;">ทักษะ</th>
                            <th style="width: 10%;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><strong><?php echo $row["empId"]; ?></strong></td>
                            <td>
                                <?php if ($row["gender"] == "นาย") { ?>
                                <div class="gender-icon male">
                                    <i class="fas fa-male"></i>
                                </div>
                                <?php } else { ?>
                                <div class="gender-icon female">
                                    <i class="fas fa-female"></i>
                                </div>
                                <?php } ?>
                                <?php echo $row["gender"]; ?>
                            </td>
                            <td><?php echo $row["empName"]; ?></td>
                            <td>
                                <?php $age = calAge($row["birthdate"]); ?>
                                <span class="age-badge"><?php echo $age; ?></span>
                            </td>
                            <td>
                                <?php 
                                $deptClass = '';
                                switch($row["eType"]) {
                                    case 'ฝ่ายพัฒนาระบบ': 
                                        $deptClass = 'bg-primary'; 
                                        break;
                                    case 'ฝ่ายการตลาด': 
                                        $deptClass = 'bg-success'; 
                                        break;
                                    case 'ฝ่ายบัญชี': 
                                        $deptClass = 'bg-warning'; 
                                        break;
                                    case 'ฝ่ายบุคคล': 
                                        $deptClass = 'bg-danger'; 
                                        break;
                                    default: 
                                        $deptClass = 'bg-secondary';
                                }
                                ?>
                                <span class="department-badge <?php echo $deptClass; ?> bg-opacity-10 text-<?php echo str_replace('bg-', '', $deptClass); ?>"><?php echo $row["eType"]; ?></span>
                            </td>
                            <td>
                                <?php 
                                $skills = explode(',', $row["skill"]);
                                foreach($skills as $skill) {
                                    echo '<span class="badge badge-skill">'.trim($skill).'</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row["empId"]; ?>" class="btn btn-sm btn-outline-info me-1"><i class="fas fa-edit"></i></a>
                                <a href="delete.php?id=<?php echo $row["empId"]; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('คุณต้องการลบพนักงานรายนี้หรือไม่?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <a href="add.php" class="btn btn-add">
                    <i class="fas fa-plus-circle me-1"></i> เพิ่มพนักงานใหม่
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>