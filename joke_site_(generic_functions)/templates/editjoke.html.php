<form action="" method="post">
    <input type="hidden" name="jokeid" 
        value="<?php if (isset($joke)): ?>
            <?=$joke['id']?>
        <?php endif; ?>
    ?>">
    <label for="joketext">Type your joke here:
    </label>
    <textarea id="joketext" name="joketext" rows="3"
        cols="40"><?php if (isset($joke)): ?>
        <?=$joke['joketext']?>
    <?php endif; ?></textarea>
    <input type="submit" name="submit" value="Save">
</form>