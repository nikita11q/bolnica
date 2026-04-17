<?php
session_start();
include 'db.php';
if (!isset($_SESSION['doctor_id'])) { header("Location: index.html"); exit(); }

if (isset($_POST['add'])) {
    $f = $_POST['f']; $l = $_POST['l']; $m = $_POST['m'];
    $d = $_POST['d']; $p = $_POST['p']; $g = $_POST['g'];
    $ps = $_POST['ps']; $s = $_POST['s'];
    $ph = $_POST['ph'];

    $conn->query("INSERT INTO patient (f_name, l_name, m_name, b_date, policy, gender, passport, snils, phone) 
                  VALUES ('$f', '$l', '$m', '$d', '$p', '$g', '$ps', '$s', '$ph')");
    header("Location: dashboard.php");
    exit();
}

$search = $_GET['q'] ?? '';
$patient = $conn->query("SELECT * FROM patient WHERE f_name LIKE '%$search%' OR l_name LIKE '%$search%' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель врача</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <span>Врач:<b>Воробей Джек Алимович<b><?php echo $_SESSION['doctor_name'];?></b></span>
    <div class="nav-right">
        <button id="show-gif" class="btn-surprise">😺</button>
        <a href="logout.php" class="btn-logout">Выйти</a>
    </div>
</nav>

<div class="container">
    <div class="card">
        <h3>Регистрация нового больного</h3>
        <form method="POST" class="grid-form">
            <input type="text" name="l" placeholder="Фамилия" required>
            <input type="text" name="f" placeholder="Имя" required>
            <input type="text" name="m" placeholder="Отчество" required>
            <input type="text" name="ph" placeholder="Номер телефона" required>
            <input type="date" name="d" required>
            <input type="text" name="p" placeholder="Полис" required>
            <select name="g">
                <option value="Мужской">Мужской</option>
                <option value="Женский">Женский</option>
            </select>
            <input type="text" name="ps" placeholder="Паспорт">
            <input type="text" name="s" placeholder="СНИЛС">
            <button type="submit" name="add" class="full-w">Добавить запись</button>
        </form>
    </div>

    <div class="card">
        <h3>Список пациентов</h3>
        <form method="GET" style="display:flex; gap:10px;">
            <input type="text" name="q" placeholder="Поиск по имени или фамилии..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" style="width:150px;">Найти</button>
        </form>
        <table>
            <tr>
                <th>ФИО</th>
                <th>Дата рожд.</th>
                <th>Полис</th>
                <th>Документы</th>
                <th>Номер телефона</th>
            </tr>
            <?php if ($patient && $patient->num_rows > 0): ?>
                <?php while($row = $patient->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['l_name'] . ' ' . $row['f_name'] . ' ' . $row['m_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['b_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['policy']); ?></td>
                    <td style="font-size:12px;">
                        Паспорт: <?php echo htmlspecialchars($row['passport'] ?? ''); ?><
                        СНИЛС: <?php echo htmlspecialchars($row['snils'] ?? ''); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['phone'] ?? ''); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Нет пациентов</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div id="video-overlay" class="video-overlay" onclick="closeVideo()">
    <div class="video-content">
        <video id="myVideo" width="320" loop muted playsinline>
            <source src="kotik.mp4" type="video/mp4">
        </video>
        <p>Нажмите в любом месте, чтобы закрыть</p>
    </div>
</div>

<script>
    const overlay = document.getElementById('video-overlay');
    const video = document.getElementById('myVideo');
    document.getElementById('show-gif').onclick = function(e) {
        e.preventDefault();
        overlay.style.display = 'flex';
        video.play();
    };
    function closeVideo() {
        overlay.style.display = 'none';
        video.pause(); video.currentTime = 0;
    }
</script>
</body>
</html>
