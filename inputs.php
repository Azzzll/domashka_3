<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form action="" method="post">
    <label for="text">Текст:</label>
    <input type="text" id="text" name="text"><br><br>

    <label for="email">Емейл:</label>
    <input type="email" id="email" name="email"><br><br>

    <label for="number">Номер:</label>
    <input type="number" id="number" name="number"><br><br>

    <label for="select">Выбор:</label>
    <select id="select" name="select">
        <option value="option1">Опция 1</option>
        <option value="option2">Опция 2</option>
        <option value="option3">Опция 3</option>
    </select><br><br>

    <input type="radio" id="radio1" name="radio" value="option1">
    <label for="radio1">Опция 1</label><br>
    <input type="radio" id="radio2" name="radio" value="option2">
    <label for="radio2">Опция 2</label><br><br>

    <input type="checkbox" id="checkbox" name="checkbox" value="yes">
    <label for="checkbox">Чекбокс</label><br><br>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password"><br><br>

    <input type="submit" value="Сохранить">
</form>

</body>
</html>
<?php
$errors = [];
$formData = [];

// Обработка формы при отправке
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация текстового поля
    if (empty($_POST['text'])) {
        $errors['text'] = "Поле 'Текст' обязательно для заполнения.";
    } else {
        $formData['text'] = htmlspecialchars(trim($_POST['text']));
    }

    // Валидация мэйла
    if (empty($_POST['email'])) {
        $errors['email'] = "Поле 'Эмейл' обязательно для заполнения.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Некорректный формат email.";
    } else {
        $formData['email'] = htmlspecialchars(trim($_POST['email']));
    }

    // Валидация номера
    if (empty($_POST['number'])) {
        $errors['number'] = "Поле 'Номер' обязательно для заполнения.";
    } elseif (!is_numeric($_POST['number'])) {
        $errors['number'] = "Поле 'Номер' должно содержать только цифры.";
    } else {
        $formData['number'] = htmlspecialchars(trim($_POST['number']));
    }

    // Валидация выбора
    if (empty($_POST['select'])) {
        $errors['select'] = "Выберите опцию.";
    } else {
        $formData['select'] = htmlspecialchars(trim($_POST['select']));
    }

    // Валидация радио
    if (empty($_POST['radio'])) {
        $errors['radio'] = "Выберите одну из опций.";
    } else {
        $formData['radio'] = htmlspecialchars(trim($_POST['radio']));
    }

    // Валидация чекбокса
    if (!isset($_POST['checkbox'])) {
        $errors['checkbox'] = "Необходимо согласие с условиями.";
    } else {
        $formData['checkbox'] = htmlspecialchars(trim($_POST['checkbox']));
    }

    // Валидация пароля
    if (empty($_POST['password'])) {
        $errors['password'] = "Поле 'Пароль' обязательно для заполнения.";
    } else {
        $formData['password'] = htmlspecialchars(trim($_POST['password']));
    }

    // Если ошибок нет, логируем данные
    if (empty($errors)) {
        $formData['timestamp'] = date('Y-m-d H:i:s'); 
        $logFile = 'form_data.json';

        if (file_exists($logFile)) {
            $existingData = json_decode(file_get_contents($logFile), true);
            if (!is_array($existingData)) {
                $existingData = [];
            }
        } else {
            $existingData = [];
        }

        $existingData[] = $formData;
        file_put_contents($logFile, json_encode($existingData, JSON_PRETTY_PRINT));

        echo "<h2>Данные успешно отправлены!</h2>";
        exit;
    }
    else {
        echo '<div style="color: red;">';
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';

    }
}

