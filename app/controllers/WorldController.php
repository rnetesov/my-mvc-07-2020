<?php


namespace Controllers;


use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Server\RequestHandlerInterface;
use Services\CityService;
use Services\CountryService;
use Twig\Environment;

class CountryController
{
	protected $twig;
	protected $countryService;
	protected $cityService;

	public function __construct(Environment $twig, CountryService $countryService, CityService $cityService)
	{
		$this->twig = $twig;
		$this->countryService = $countryService;
		$this->cityService = $cityService;
	}

	public function index(ServerRequest $request, RequestHandlerInterface $handler)
	{
		$countries = $this->countryService->getAll();
		return new HtmlResponse($this->twig->render('country/table.twig', compact("countries")));
	}

	public function continent(ServerRequest $request, RequestHandlerInterface $handler)
	{
		$continentName = str_replace('-', ' ', $request->getAttribute('continent'));
		$countries = $this->countryService->findByContinent($continentName);
		if (!$countries) return $handler->handle($request);
		return new HtmlResponse($this->twig->render('country/table.twig', compact("countries")));
	}

	public function region(ServerRequest $request, RequestHandlerInterface $handler)
	{
		$regionName = str_replace('-', ' ', $request->getAttribute('region'));
		$countries = $this->countryService->findByRegion($regionName);
		if (!$countries) return $handler->handle($request);
		return new HtmlResponse($this->twig->render('country/table.twig', compact("countries")));
	}


	public function governmentForm(ServerRequest $request, RequestHandlerInterface $handler)
	{
		$governmentForm = str_replace('-', ' ', $request->getAttribute('name'));
		$countries = $this->countryService->findByGovernment($governmentForm);
		if (!$countries) return $handler->handle($request);
		return new HtmlResponse($this->twig->render('country/table.twig', compact("countries")));
	}

	public function citiesCountryName(ServerRequest $request, RequestHandlerInterface $handler)
	{
		$countryName = str_replace('-', ' ', $request->getAttribute('countryName'));
		$cities = $this->cityService->findByCountryName($countryName);
		if (!$cities) return $handler->handle($request);
		return new HtmlResponse($this->twig->render('city/table.twig', compact("cities")));
	}

}