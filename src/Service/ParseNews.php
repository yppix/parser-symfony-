<?php

namespace App\Service;

use App\Entity\NewsList;
use App\Entity\ThemeNews;
use App\Repository\NewsListRepository;
use App\Repository\ThemeNewsRepository;
use DiDom\Document;

class ParseNews
{

//Use DiDOM parser and get news
    public function getDomAllNews(
        $data,
        CurlNewsPage $curlNewsPage,
        NewsListRepository $newsListRepository,
        ThemeNewsRepository $themeNewsRepository
    ): array
    {
        //get news links
        $document = new Document($data);
        $news = $document->find('div.js-news-feed-list>a.news-feed__item');

        $posts_array = [];

        foreach ($news as $element) {
            $theme_obj = new ThemeNews();
            $news_obj = new NewsList();

            $text = trim($element->find('span.news-feed__item__title')[0]->text());
            $theme_date = $element->find('span.news-feed__item__date-text')[0]->text();

            $inf = $curlNewsPage->getPage($element->href);
            $information = new Document($inf);
            $images = $information->first('div.article__main-image__wrap>img');
            $src = $information->find('div.article__main-image__wrap>img');
            //get theme and date
            $pos = substr($theme_date, strpos($theme_date, ','));
            $pos = preg_replace('/,/', '', $pos);
            $pos = date("Y-m-d") . " " . $pos;

            $theme = mb_substr($theme_date, 0, (strpos($theme_date, ',')));
            $theme = preg_replace('/[^a-zA-Zа-яА-Я\s]/iu', '', $theme);
            $createTheme = $themeNewsRepository->create($theme, $theme_obj);
            //get short txt
            $text = $this->textCut($text);

            //put all data to table
            $posts_array[] = [
                'text' => $text,
                'theme' => $theme,
                'time_publication' => $pos,
                'images' => $images,
                'src' => $src,
                'link' => $element->href
            ];

            $createNews = $newsListRepository->create($posts_array, $news_obj, $themeNewsRepository);
        }
        return $posts_array;
    }


    private function textCut($str, $length = 200)
    {
        if (strlen($str) <= $length) {
            return $str;
        } else {
            $str = mb_substr($str, 0, $length) . '...';
            return $str;
        }
    }


}

?>