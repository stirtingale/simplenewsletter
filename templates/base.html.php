<!DOCTYPE html>
<html>

<head>
    <title>Email Content Formatter</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.2/tinymce.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .preview-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .buttons {
            margin: 20px 0;
        }

        button {
            padding: 10px 20px;
            background: #ff5538;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #e64a30;
        }

        #formatted-output {
            width: 100%;
            height: 300px;
            margin-top: 20px;
            font-family: monospace;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
    <script>
        tinymce.init(<?= json_encode($tinymceConfig) ?>);
    </script>
</head>

<body>
    <div class="container">
        <?php include 'editor.html.php'; ?>
    </div>
</body>

</html>