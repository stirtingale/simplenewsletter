<form method="post" id="formatter">
    <div style="margin-bottom: 20px;">
        <label for="subject">Email Subject:</label>
        <?php
        $placeholder = str_replace(
            ['{date}', '{emoji}'],
            [date('jS F'), '🌟'],
            $_ENV['SENDY_TITLE']
        );
        ?>
        <input type="text"
            id="subject"
            name="subject"
            style="width: 100%; padding: 8px; margin-top: 5px;"
            value="<?php echo htmlspecialchars($_POST['subject'] ?? $placeholder); ?>"
            placeholder="<?php echo htmlspecialchars($placeholder); ?>">
    </div>

    <textarea id="editor" name="content"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>

    <div class="buttons" style="margin-top: 20px; padding: 10px; background: #f5f5f5;">
        <button type="submit" name="action" value="format">Format</button>
    </div>
</form>

<?php if (!empty($preview)): ?>
    <div class="preview-container">
        <h3>Formatted Result:</h3>
        <div id="result"><?php echo $preview; ?></div>
        <div class="action-buttons" style="margin-top: 20px;display:flex;gap:0.45em;">
            <form method="post" target="_self">
                <input type="hidden" name="content" value="<?php echo htmlspecialchars($_POST['content'] ?? ''); ?>">
                <input type="hidden" name="subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                <button type="submit" name="action" value="send">Create in Sendy</button>
            </form>
            <?php if (!empty($success)): ?>
                <a href="<?php echo $_ENV['SENDY_URL']; ?>/app?i=<?php echo $_ENV['SENDY_BRAND_ID']; ?>" target="_blank">
                    <button type="button">Success: View Brand</button>
                </a>
            <?php endif; ?>
        </div>
        <?php if (isset($sendyResponse)): ?>
            <div class="sendy-response" style="margin-top: 20px; padding: 10px; background: #f5f5f5;">
                <pre><?php echo htmlspecialchars($sendyResponse); ?></pre>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>