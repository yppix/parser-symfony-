<?php


namespace App\Controller;


use App\Entity\News;
use App\Entity\NewsList;
use App\Entity\ThemeNews;
use App\Form\NewsType;
use App\Repository\NewsListRepository;
use App\Repository\ThemeNewsRepository;
use App\Service\CurlNewsPage;
use App\Service\ParseNews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ParserController extends AbstractController
{
    /**
     * @Route("/", name="news_homepage")
     */

    public function addNews(Request $request, CurlNewsPage $curlNewsPage, ParseNews $parseNews,
        NewsListRepository $newsListRepository, ThemeNewsRepository $themeNewsRepository) {



        $form = $this->createForm(NewsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $required_news = $form->getData();
            $data = $curlNewsPage->getPage($required_news["url"]);
            $parseNews = $parseNews->getDomAllNews($data, $curlNewsPage, $newsListRepository, $themeNewsRepository);

            return $this->render('news/news-list.html.twig',['news_array'=> $parseNews]);
        }
        return $this->render('news/homepage.html.twig',[ 'form' => $form->createView()]);

    }



}