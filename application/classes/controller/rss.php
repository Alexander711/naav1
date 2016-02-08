<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Rss extends Controller
{
    function action_index()
    {
        $newsportal = ORM::factory('newsportal')->where('active', '=', 1)->order_by('date', 'DESC');
        $newsservice = ORM::factory('newsservice')->where('active', '=', 1)->order_by('date_create', 'DESC');
        $news_world = ORM::factory('newsworld')->where('active', '=', 1)->order_by('date', 'DESC');
        $info = array(
            'title' => 'Новости',
            'language' => 'ru',
            'description' => 'Новости от '.date('d.m.Y H:i'),
            'pubDate' => time(),
        );
        $items = array();
        foreach ($newsportal->find_all() as $news)
        {
            $items[] = array(
                'title' => $news->title,
                'url' => URL::base().'news/association/'.$news->id,
                'guid' => URL::base().'news/association/'.$news->id,
                'description' => strip_tags(htmlspecialchars($news->text)),
                'pubDate' => $news->date
            );
        }
        foreach ($newsservice->find_all() as $news)
        {
            $items[] = array(
                'title' => $news->service->name.': '.$news->title,
                'url' => URL::base().'news/'.$news->id,
                'guid' => URL::base().'news/'.$news->id,
                'description' => strip_tags(htmlspecialchars($news->text)),
                'pubDate' => $news->date_create
            );
        }
        foreach ($news_world->find_all() as $news)
        {
            $items[] = array(
                'title' => $news->title,
                'url' => URL::base().'news/world/'.$news->id,
                'guid' => URL::base().'news/world/'.$news->id,
                'description' => strip_tags(htmlspecialchars($news->text)),
                'pubDate' => $news->date
            );
        }
        $this->response->headers('Content-type', 'text/xml');
        echo Feed::create($info, $items, 'rss', 'utf-8');
    }
}