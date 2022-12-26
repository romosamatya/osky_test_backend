<style>
  .fade-in {
    opacity: 0;
    transition: opacity 0.5s;
  }
</style>

<div>
  <?php
  // Download the JSON file
  $json = file_get_contents('http://test.osky.dev/101/data.json');

  // Parse the JSON into a PHP object
  $data = json_decode($json);

  // Sort the data by title
  usort($data, function ($a, $b) {
    return strcmp($a->title, $b->title);
  });

  // Create a div for each news item
  foreach ($data as $item) {
    echo '<div class="fade-in">';
    echo '<h2>' . $item->title . '</h2>';

    // Check if link is a string or an array
    if (is_array($item->link)) {
      // Filter the array to get only URLs
      $urls = array_filter($item->link, function ($link) {
        return strpos($link, 'http') === 0;
      });
      if (!empty($urls)) {
        // Display the first URL as a link
        echo '<a href="' . $urls[0] . '" target="_blank">Read More</a>';
      }
    } else {
      // Display the link as a link if it is a URL
      if (strpos($item->link, 'http') === 0) {
        echo '<a href="' . $item->link . '" target="_blank">Read More</a>';
      }
    }

    echo '<p>' . $item->description . '</p>'; // Use p tag for the content
    $date = strtotime($item->pubDate[0]); // Convert date string to Unix timestamp

    // Format date as "Day, Date, English ordinal suffix, 'of', Long Month Name, Year, Hour with no leading zero, Minute, and Ante/Post Meridiem"
    $formattedDate = date('l, j', $date);
    $formattedDate .= date('S', $date) . " of ";
    $formattedDate .= date('F Y g:i', $date);
    $formattedDate .= date('a', $date);

    echo '<p><i>' . $formattedDate . '</i></p>'; // Display formatted date
    echo '</div>';
  }
  ?>
</div>

<script>
  // Get all news items
  const items = document.querySelectorAll('.fade-in');

  // Fade in each item
  let i = 0;
  function fadeIn() {
    if (i < items.length) {
      items[i].style.opacity = 1;
      i++;
      setTimeout(fadeIn, 500);
    }
  }
  fadeIn();
</script>