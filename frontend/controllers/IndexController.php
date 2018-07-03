<?php


namespace frontend\controllers;


use common\controllers\TopController;
use Crew\Unsplash\HttpClient;
use Crew\Unsplash\Search;

class IndexController extends TopController
{
    public $defaultAction = 'index';
    
    public function actionIndex()
    {
        return $this->render('index', ['search_string' => $this->get('search_string', '美女')]);
    }
    
    public function actionLists()
    {
        /*
         *  $search	string	Required
            $page	int	Opt (Default: 1)
            $per_page	int	Opt (Default: 10 / Maximum: 30)
            $orientation	string	Opt (Default: null / Available: "landscape", "portrait", "squarish")
            $collections	string	Opt (Default: null / If multiple, comma-separated)
        */
        $search_string = $this->get('search_string', '美女');
        $page          = $this->get('page', 1);
        $page_size     = $this->get('page_size', 30);;
        $orientation = null;
        
        /**
         * [resultClassName:Crew\Unsplash\PageResult:private] => Crew\Unsplash\Photo
         * [total:Crew\Unsplash\PageResult:private] => 13717
         * [totalPages:Crew\Unsplash\PageResult:private] => 915
         * [results:Crew\Unsplash\PageResult:private] => Array
         * (
         * [0] => Array
         * (
         * [id] => p4orVxNl5Ko
         * [created_at] => 2015-10-19T10:25:30-04:00
         * [updated_at] => 2018-05-18T13:01:35-04:00
         * [width] => 3840
         * [height] => 5760
         * [color] => #6D7D39
         * [description] => A low-angle shot of a green canopy of bamboos and trees
         * [urls] => Array
         * (
         * [raw] => https://images.unsplash.com/photo-1445264718234-a623be589d37?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjMwMzczfQ&s=1eb24f59ed08c4859efbfc486afd901f
         * [full] => https://images.unsplash.com/photo-1445264718234-a623be589d37?ixlib=rb-0.3.5&q=85&fm=jpg&crop=entropy&cs=srgb&ixid=eyJhcHBfaWQiOjMwMzczfQ&s=970a25c7484a4b44cb620382bf1ebae0
         * [regular] => https://images.unsplash.com/photo-1445264718234-a623be589d37?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max&ixid=eyJhcHBfaWQiOjMwMzczfQ&s=0731bdc8671c3fb63f19460e7de5c685
         * [small] => https://images.unsplash.com/photo-1445264718234-a623be589d37?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjMwMzczfQ&s=42ecc14eb35ea23b50b4d02985d08bfc
         * [thumb] => https://images.unsplash.com/photo-1445264718234-a623be589d37?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjMwMzczfQ&s=7410381c5349e0ab28a6db3b38746cdd
         * )
         *
         * [links] => Array
         * (
         * [self] => https://api.unsplash.com/photos/p4orVxNl5Ko
         * [html] => https://unsplash.com/photos/p4orVxNl5Ko
         * [download] => https://unsplash.com/photos/p4orVxNl5Ko/download
         * [download_location] => https://api.unsplash.com/photos/p4orVxNl5Ko/download
         * )
         *
         * [categories] => Array
         * (
         * )
         *
         * [sponsored] =>
         * [likes] => 262
         * [liked_by_user] =>
         * [current_user_collections] => Array
         * (
         * )
         *
         * [slug] =>
         * [user] => Array
         * (
         * [id] => EHCERIL6Ywc
         * [updated_at] => 2018-06-28T09:47:19-04:00
         * [username] => kazuend
         * [name] => kazuend
         * [first_name] => kazuend
         * [last_name] =>
         * [twitter_username] => kazuend
         * [portfolio_url] => http://kazuend.jp
         * [bio] => I’m a Photographer based in Japan. You can get in touch with me through Twitter (@kazuend)  web:kazuend.jp
         * [location] => Japan
         * [links] => Array
         * (
         * [self] => https://api.unsplash.com/users/kazuend
         * [html] => https://unsplash.com/@kazuend
         * [photos] => https://api.unsplash.com/users/kazuend/photos
         * [likes] => https://api.unsplash.com/users/kazuend/likes
         * [portfolio] => https://api.unsplash.com/users/kazuend/portfolio
         * [following] => https://api.unsplash.com/users/kazuend/following
         * [followers] => https://api.unsplash.com/users/kazuend/followers
         * )
         *
         * [profile_image] => Array
         * (
         * [small] => https://images.unsplash.com/profile-1457277118861-3bd64693dc05?ixlib=rb-0.3.5&q=80&fm=jpg&crop=faces&cs=tinysrgb&fit=crop&h=32&w=32&s=6a19c0055dd044aef58662bc46ff191e
         * [medium] => https://images.unsplash.com/profile-1457277118861-3bd64693dc05?ixlib=rb-0.3.5&q=80&fm=jpg&crop=faces&cs=tinysrgb&fit=crop&h=64&w=64&s=e14003aca545dafa54e1a4d038fa5c44
         * [large] => https://images.unsplash.com/profile-1457277118861-3bd64693dc05?ixlib=rb-0.3.5&q=80&fm=jpg&crop=faces&cs=tinysrgb&fit=crop&h=128&w=128&s=cc0729444717d0c4e617948bee003449
         * )
         *
         * [instagram_username] => kazuend
         * [total_collections] => 0
         * [total_likes] => 15
         * [total_photos] => 180
         * )
         *
         * [tags] => Array
         * (
         * [0] => Array
         * (
         * [title] => outdoor
         * )
         *
         * [1] => Array
         * (
         * [title] => tree
         * )
         *
         * [2] => Array
         * (
         * [title] => sunny
         * )
         *
         * [3] => Array
         * (
         * [title] => greenery
         * )
         *
         * [4] => Array
         * (
         * [title] => zen
         * )
         *
         * [5] => Array
         * (
         * [title] => tranquil
         * )
         *
         * [6] => Array
         * (
         * [title] => nature
         * )
         *
         * [7] => Array
         * (
         * [title] => botanical
         * )
         *
         * [8] => Array
         * (
         * [title] => wood
         * )
         *
         * [9] => Array
         * (
         * [title] => leafe
         * )
         *
         * [10] => Array
         * (
         * [title] => mountain
         * )
         *
         * [11] => Array
         * (
         * [title] => high
         * )
         *
         * [12] => Array
         * (
         * [title] => green
         * )
         *
         * [13] => Array
         * (
         * [title] => china
         * )
         *
         * [14] => Array
         * (
         * [title] => stone
         * )
         *
         * [15] => Array
         * (
         * [title] => pathway
         * )
         *
         * [16] => Array
         * (
         * [title] => stem
         * )
         *
         * [17] => Array
         * (
         * [title] => sun
         * )
         *
         * [18] => Array
         * (
         * [title] => autumn
         * )
         *
         * [19] => Array
         * (
         * [title] => asian
         * )
         *
         * )
         *
         * [photo_tags] => Array
         * (
         * [0] => Array
         * (
         * [title] => outdoor
         * )
         *
         * [1] => Array
         * (
         * [title] => tree
         * )
         *
         * [2] => Array
         * (
         * [title] => sunny
         * )
         *
         * [3] => Array
         * (
         * [title] => greenery
         * )
         *
         * [4] => Array
         * (
         * [title] => zen
         * )
         *
         * )
         *
         * )
         */
        
        HttpClient::init([
            'applicationId' => '897275c924a7e959bbc3221d2119cf3b16f74aad91e1ffb5bb901e557e554bda',
            //            'secret'        => 'f3bbce3f3790d8f9daf1de17e899ca35d59f7c43e838514cd1e76f9e22d66784',
            'callbackUrl'   => 'https://your-application.com/oauth/callback',
            'utmSource'     => 'Sunlands Gallery',
        ]);
    
        $cacheKey = "$search_string-$page-$page_size-$orientation";
        if ($cache = $this->cache()->get($cacheKey)) {
            $photos = $cache;
        } else {
            if ($photos = Search::photos($search_string, $page, $page_size, $orientation)->getResults()) {
                $this->cache()->set($cacheKey, $photos);
            }
        }
        
        foreach ($photos as &$photo) {
            $photo = [
                'author_url'  => $photo['user']['links']['html'],
                'author_name' => $photo['user']['username'],
                'source_url'  => $photo['links']['html'],
                'src'         => $photo['urls']['thumb'],
                'width'       => 200,
                'height'      => 200 * $photo['height'] / $photo['width'],
                'download'    => $photo['links']['download'],
            ];
        }
        
        return json_encode($photos);
    }
}
