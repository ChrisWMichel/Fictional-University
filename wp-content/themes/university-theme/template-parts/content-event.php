<div class="event-summary" >
    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
        <span class="event-summary__month"><?php 
            $eventDate = new DateTime(get_field('event_date'));
            echo $eventDate->format('M');
        ?></span>
        <span class="event-summary__day"><?php 
            $eventDate = new DateTime(get_field('event_date'));
            echo $eventDate->format('d');
        ?></span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <?= has_excerpt() ? wp_trim_words(get_the_excerpt(), 15) : wp_trim_words(get_the_content(), 15); ?>
        <p><a class="gray nu" style="background-color: transparent;" href="<?php the_permalink(); ?>">Learn more &raquo;</a></p>
    </div>
    <div class="generic-content">
        
    </div>
    
</div>