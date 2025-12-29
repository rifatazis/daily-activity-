<!DOCTYPE html>
<html>
<head>
    <title>Daily Activity</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            margin-top: 0;
        }

        /* LOGOUT */
        .logout {
            position: fixed;
            top: 15px;
            right: 20px;
        }

        .logout button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout button:hover {
            background: #c0392b;
        }

        /* LAYOUT */
        .container {
            display: flex;
            gap: 25px;
            margin-top: 20px;
        }

        .box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        /* FORM */
        input[type=text] {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .score-btn {
            border: none;
            padding: 8px 14px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
        }

        .plus { background: #2ecc71; }
        .equal { background: #95a5a6; }
        .minus { background: #e74c3c; }

        /* SCORE COLOR */
        .score-plus { color: #2ecc71; font-weight: bold; }
        .score-equal { color: #7f8c8d; font-weight: bold; }
        .score-minus { color: #e74c3c; font-weight: bold; }

        /* CALENDAR */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .calendar a {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            color: black;
            background: white;
        }

        .calendar a.active {
            background: #dff9fb;
            border-color: #22a6b3;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #f1f1f1;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<!-- LOGOUT -->
<div class="logout">
    <form method="POST" action="/logout">
        <?php echo csrf_field(); ?>
        <button>Logout</button>
    </form>
</div>

<h2>📅 Daily Activity</h2>

<div class="container">

    <!-- LEFT -->
    <div class="box">

        <h3>Tambah Aktivitas</h3>

        <form method="POST" action="/activity">
            <?php echo csrf_field(); ?>
            <input type="text" name="activity" placeholder="Tulis aktivitas..." required>
            <br><br>

            <button class="score-btn plus" type="submit" name="score" value="1">+</button>
            <button class="score-btn equal" type="submit" name="score" value="0">=</button>
            <button class="score-btn minus" type="submit" name="score" value="-1">-</button>
        </form>

        <hr>

        <!-- CALENDAR -->
        <h3>🗓️ <?php echo e($month->translatedFormat('F Y')); ?></h3>

        <div class="calendar">
            <?php
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();
            ?>

            <?php for($i = 0; $i < $start->dayOfWeek; $i++): ?>
                <div></div>
            <?php endfor; ?>

            <?php for($day = 1; $day <= $end->day; $day++): ?>
                <?php
                    $date = $month->copy()->day($day)->toDateString();
                    $total = $calendarData[$date]->total ?? 0;
                ?>

                <a href="/dashboard?date=<?php echo e($date); ?>"
                   class="<?php echo e($date == $selectedDate ? 'active' : ''); ?>">

                    <strong><?php echo e($day); ?></strong><br>
                    <span class="
                        <?php echo e($total > 0 ? 'score-plus' : ($total < 0 ? 'score-minus' : 'score-equal')); ?>

                    ">
                        <?php echo e($total); ?>

                    </span>
                </a>
            <?php endfor; ?>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="box">

        <h3>
            Aktivitas
            (<?php echo e(\Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y')); ?>)
        </h3>

        <p><strong>Skor Hari Ini:</strong> <?php echo e($score); ?></p>

        <?php if($activities->isEmpty()): ?>
            <p>Belum ada aktivitas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Aktivitas</th>
                        <th>Skor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($a->log_time)->format('H:i')); ?></td>
                            <td><?php echo e($a->activity); ?></td>
                            <td>
                                <span class="
                                    <?php echo e($a->score == 1 ? 'score-plus' : ($a->score == -1 ? 'score-minus' : 'score-equal')); ?>

                                ">
                                    <?php echo e($a->score == 1 ? '+' : ($a->score == -1 ? '-' : '=')); ?>

                                </span>
                            </td>
                            <td>
                                <form method="POST" action="/activity/<?php echo e($a->id); ?>"
                                      onsubmit="return confirm('Hapus aktivitas ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="delete-btn">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
<?php /**PATH C:\laragon\www\daily-activity\resources\views/dashboard.blade.php ENDPATH**/ ?>