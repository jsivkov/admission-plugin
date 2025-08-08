<?php if (!empty($_GET['success'])): ?>
    <div class="notice notice-success">Моля, проверете вашия имейл за потвърждение.</div>
<?php endif; ?>

<form method="post">
    <input type="text" name="first_name" placeholder="Име" required>
    <input type="text" name="middle_name" placeholder="Презиме" required>
    <input type="text" name="last_name" placeholder="Фамилия" required>
    <input type="text" name="egn" placeholder="ЕГН" pattern="\d{10}" required>
    <input type="tel" name="phone" placeholder="Телефон" required>
    <input type="email" name="email" placeholder="Имейл" required>
    <button type="submit" name="nvna_admission_submit">Кандидатствай</button>
</form>