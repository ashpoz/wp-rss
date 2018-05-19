<?php
/**
Template Name: Newsroom Page
 */

get_header('bare'); 

?>

<!-- TODO: you can delete encapsulation once page is live -->
<div id="page-2018">

<!-- This may go in new 2018 design -->
<!-- <div class="hero header">
	<div class="row d-flex align-center">
		<h1 class="hero_text">Spiceworks Newsroom</h1>
	</div>
</div> -->

<?php get_template_part( 'template-parts/nav', 'secondary' ); ?>

<div class="row">
	<div class="columns">
		<div id="page-sidebar-right" role="main">
		<div class="row">
			<div class="columns medium-8">

				<?php do_action( 'foundationpress_before_content' ); ?>
				<!-- Main Content -->
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="" id="post-<?php the_ID(); ?>">

					    <?php do_action( 'foundationpress_page_before_entry_content' ); ?>
					    <div class="entry-content">
					        <?php the_content(); ?>
					    </div>

					</article>
				<?php endwhile;?>
				

			<!-- Fetch RSS Feeds for Research, Corporate, and Press blogs -->
			<!-- Organized by date, and posts in the last 6 months -->

				<div class="blog-container">
				<?php if(function_exists('fetch_feed')) {

				include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator
				$feedlist = array('Tech Crunch' => 'https://techcrunch.com/feed/',
							  'Mashable' => 'https://mashable.com/rss/',
							  'Gixmodo' => 'https://gizmodo.com/rss',
							  'WP Tavern' => 'https://wptavern.com/feed'
							  ); // specify the rss feeds in array


				$feed = fetch_feed($feedlist); //fetch feeds into simplePie mashup
				$new = array();

				foreach ($feed->get_items() as $key => $item) {

					$datelimit = time() - (1*30*24*60*60); // calcutate last month

					if ($item->get_date('U') >= $datelimit) {
						$new[] = $item;
					}

				}
				// $limit = $feed->get_item_quantity(50); // specify number of items
				// $items = $feed->get_items(0, $limit); // create an array of items
			}
				$i = 0; // counter loop iterations
				if ($feed == 0) echo '<div>The feed is either empty or unavailable.</div>';
				else foreach ($new as $key => $item) : 

					$description = strip_tags($item->get_description()); // strip HTML tags in description
					$excerpt = substr($description, 0, 200); // get description set to 200 chars
					$content = $item->get_content(); // get content
					preg_match('/<*img[^>]*src *= *["\']?([^"\']*)/i', $item->get_content(), $matches); // match content with img tag
					$host = $matches[1];
					preg_match('/[^<]+\.[^.]+$/', $host, $matches);


					// get permalink
					$url = $item->get_permalink();
					// get url path
					$path = parse_url($url, PHP_URL_PATH);
					// store url path name
					$firstSubDir = explode('/', $path)[1]; // [0] is the domain [1] is the first subdirectory, etc.

					// social sharing links
					$facebook = 'http://www.facebook.com/sharer.php?u=';
					$twitter = 'http://twitter.com/home?status';
					$linkedin = 'https://www.linkedin.com/shareArticle?mini=true&url=';
					$i++;

					// this code echoes out the blog category
					// switch statement to echo category based on url path
						switch ($firstSubDir) {
							case 'blog': $blogCat = 'Corporate Blog'; break;
							case 'press': $blogCat = 'Press Release'; break;
							case 'research': $blogCat = 'Research'; break;
							case 'blogs': $blogCat = 'Product Blog'; break;
						}

					// convert blogCat output to class
					$categoryClass = strtolower($blogCat);
					$categoryClass = str_replace(' ', '-', $categoryClass);
				?>

				<!-- The actual output -->
				<div class="blog-post card">
					<p class="gray-text"><span class="category <?php echo $categoryClass; ?>"> <?php echo $blogCat; ?></span> | <?php echo $item->get_date('F j, Y'); ?></p>
					<h4 class="post-title"><a href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_blank"><?php echo $item->get_title(); ?></a></h4>
				<?php if (preg_match('/[^<]+\.[^.]+$/', $host, $matches)) { ?>

					<!-- Temporary fix to fetch images -->
					<!-- <div class="img-container" style="background-image: url('<?php // echo $matches[0]; ?>'); background-size: cover; background-position: center;">					
					</div> -->
					<div class="card-section">
						<img class="padbot-1" src="<?php echo $host; ?>" alt="">
						<p class="post-excerpt">
						<?php echo $excerpt; if(strlen($excerpt) >= 200): echo ' [...]'; endif;?>
						</p>
						<div class="card_links">   
							<ul class="card_social">
								<li><a id="fbShare-<?php echo $i; ?>" href="" class="card_social-icon fb"></a></li>
								<li><a id="twShare-<?php echo $i; ?>" href="" class="card_social-icon twitter"></a></li>
								<li><a id="inShare-<?php echo $i; ?>" href="" class="card_social-icon linkedin"></a></li>
							</ul>
							<a class="more-link" href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_blank">Read more</a>
						</div>
					</div>
					<?php } else { ?>
					<div class="card-section">
						<p class="post-excerpt">
						<?php echo $excerpt; if(strlen($excerpt) >= 200): echo ' [...]'; endif;?>
						</p>
						<div class="card_links">  
							<ul class="card_social">
								<li><div id="fbShare-<?php echo $i; ?>" href="" class="card_social-icon fb"></div></li>
								<li><div id="twShare-<?php echo $i; ?>" href="" class="card_social-icon twitter"></div></li>
								<li><div id="inShare-<?php echo $i; ?>" href="" class="card_social-icon linkedin"></div></li>
							</ul>
							<a class="more-link" href="<?php echo $item->get_permalink(); ?>" alt="<?php echo $item->get_title(); ?>" target="_blank">Read more</a>
						</div>
					</div>
					<?php } ?>
				</div>
				<script>
					$("#fbShare-<?php echo $i ?>").on("click",function(){
					    window.open("<?php echo $facebook . $item->get_permalink() ?>", "pop", "width=600, height=400, scrollbars=no");
					    return false;
					});
					$("#twShare-<?php echo $i ?>").on("click",function(){
					    window.open("<?php echo $twitter . $item->get_permalink() ?>", "pop", "width=600, height=400, scrollbars=no");
					    return false;
					});
					$("#inShare-<?php echo $i ?>").on("click",function(){
					    window.open("<?php echo $linkedin . $item->get_permalink() ?>", "pop", "width=600, height=400, scrollbars=no");
					    return false;
					});
				</script>

				<?php endforeach; ?>

				</div>
			</div>

			<?php do_action( 'foundationpress_after_content' ); ?>
			<div class="sidebar-container columns medium-4">
			<?php get_sidebar(); ?>
			</div>

			</div>

	</div>
</div>
</div>

</div> <!-- end #page-2018 -->
<!-- Press Home Scripts -->
<script src="wp-content/themes/press/assets/js/press-home.js"></script>
<?php get_footer();
