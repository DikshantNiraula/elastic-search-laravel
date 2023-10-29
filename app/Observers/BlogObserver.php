<?php

namespace App\Observers;

use App\BlogPost;
use App\Jobs\IndexBlogElasticsearchJob;
use App\Jobs\RemoveBlogElasticsearchJob;

class BlogObserver
{
    /**
     * Handle the blog post "created" event.
     *
     * @param  \App\BlogPost  $blog
     * @return void
     */
    public function created(BlogPost $blog)
    {
        dispatch(new IndexBlogElasticsearchJob($blog));
    }

    /**
     * Handle the blog post "updated" event.
     *
     * @param  \App\BlogPost  $blog
     * @return void
     */
    public function updated(BlogPost $blog)
    {
        dispatch(new IndexBlogElasticsearchJob($blog));
    }

    /**
     * Handle the blog post "deleted" event.
     *
     * @param  \App\BlogPost  $blog
     * @return void
     */
    public function deleted(BlogPost $blog)
    {
        dispatch(new RemoveBlogElasticsearchJob($blog->id));
    }

    /**
     * Handle the blog post "restored" event.
     *
     * @param  \App\BlogPost  $blog
     * @return void
     */
    public function restored(BlogPost $blog)
    {
        dispatch(new IndexBlogElasticsearchJob($blog));
    }

    /**
     * Handle the blog post "force deleted" event.
     *
     * @param  \App\BlogPost  $blog
     * @return void
     */
    public function forceDeleted(BlogPost $blog)
    {
        dispatch(new RemoveBlogElasticsearchJob($blog->id));
    }
}
