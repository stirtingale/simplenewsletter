<h1>Email Content Formatter</h1>
<p>Paste your Google Docs content below and click Format to generate an email template.</p>

<form method="post" id="formatter">
    <textarea id="editor" name="content"></textarea>
    <div class="buttons">
        <button type="submit">Format for Email</button>
    </div>
</form>

<?php if (!empty($preview)): ?>
    <h2>Formatted Result</h2>
    <div class="preview-container">
        <h3>Preview:</h3>
        <?php echo $preview; ?>

        <h3>HTML Code:</h3>
        <textarea id="formatted-output"><?php echo htmlspecialchars($preview); ?></textarea>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const output = document.getElementById('formatted-output');
            const copyBtn = document.createElement('button');
            copyBtn.textContent = 'Copy HTML';
            copyBtn.onclick = function() {
                output.select();
                document.execCommand('copy');
                alert('HTML copied to clipboard!');
            };
            output.parentNode.insertBefore(copyBtn, output);
        });
    </script>
<?php endif; ?>