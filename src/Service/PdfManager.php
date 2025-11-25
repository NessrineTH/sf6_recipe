<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfManager
{
    private bool $pdfDisableSmartShrinking;

    public const SNAPPYPARAMS_SMALLMARGIN = 'SMALL_MARGIN';
    public const SNAPPYPARAMS_NOMARGIN = 'NO_MARGIN';

    public function __construct(private readonly EntityManagerInterface $em, private readonly  Environment $templating, private readonly  Pdf $snappypdf, private readonly  ParamManager $param, bool $pdfDisableSmartShrinking)
    {
        $this->pdfDisableSmartShrinking = $pdfDisableSmartShrinking;
    }

    /**
     * vue projet vers pdf
     */
    public function viewToPdf(string $viewName, array $viewParams, string $filename, $snappyParamsSupp=[], bool $content_only = false, string $snappyParamsType = '', bool $htmlonly = false): string|Response
    {
        $html = $this->templating->render($viewName, $viewParams);

        return $this->toPdf($html, $filename,$snappyParamsSupp, $content_only, $snappyParamsType, $htmlonly);
    }

    /**
     * Chemin fichier twig vers pdf (évite pb cache, etc)
     *
     */
    public function twigFilenameToPdf(string $twigFname, array $viewParams, string $filename,$snappyParamsSupp=[], bool $content_only = false, string $snappyParamsType = '', bool $htmlonly = false): string|Response
    {
        $template = '';

        if (file_exists($twigFname)) {
            $template = file_get_contents($twigFname);
        }
        if (!$template) {
            throw new Exception('Gabarit non trouvé');
        }
        $template  = $this->templating->createTemplate($template);
        $html = $template->render(
            $viewParams
        );

        return $this->toPdf($html, $filename,$snappyParamsSupp, $content_only, $snappyParamsType, $htmlonly);
    }

    private function toPdf($html, $filename,$snappyParamsSupp, $content_only = false, $snappyParamsType = '', $htmlonly = false): string|Response
    {
        if ($htmlonly) {
            return $html;
        }

        $snappyParams = [
            'margin-left' => 0,
            'margin-right' => 0,
            'margin-top' => 0,
            'margin-bottom' => 0,
            'print-media-type' => true,
            'disable-smart-shrinking' => $this->pdfDisableSmartShrinking,
        ];

        if (self::SNAPPYPARAMS_SMALLMARGIN == $snappyParamsType) {
            $snappyParams['margin-left'] = 10;
            $snappyParams['margin-right'] = 10;
            $snappyParams['margin-top'] = 10;
            $snappyParams['margin-bottom'] = 10;
        }

        if($snappyParamsSupp){
            $snappyParams = array_merge($snappyParams,$snappyParamsSupp);
        }

        if ($content_only) {
            $content = $this->snappypdf->getOutputFromHtml($html, $snappyParams);

            return $content;
        } else {
            return new Response($this->snappypdf->getOutputFromHtml($html, $snappyParams), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }
    }
}
