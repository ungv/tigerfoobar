<?php foreach ($news as $news_item) { ?>

    <h2><?= $news_item['title'] ?></h2>
    <div class="article">
        <?= $news_item['text'] ?>
    </div>
    <p>
    	<a href="news/<?= $news_item['slug'] ?>">View article</a>
    </p>

<?php } ?>