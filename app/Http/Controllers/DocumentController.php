<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::paginate(10);
        return view('documentz.index', compact('documents'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx|max:2048',
        ]);

        $file = $request->file('document');
        $path = $file->store('documents');
        $text = $this->extractText($file, $file->extension());
        
        Log::info('Extracted text:', ['text' => substr($text, 0, 1000)]); // Limit log length
        
        $result = $this->calculateMatchPercentage($text);
        
        Log::info('Match result:', $result);

        $data = [
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'match_percentage' => $result['percentage'],
            'group' => $result['group'],
            'matched_keywords' => $result['matched_keywords'],
        ];
        
        Log::info('Data to save:', $data);

        $document = Document::create($data);

        Log::info('Saved document:', $document->toArray());

        return redirect()->route('documentz.index')->with('success', 'Document uploaded and clustered successfully.');
    }

    private function extractText($file, $extension)
    {
        $path = $file->getPathname();
        try {
            if ($extension === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                return $pdf->getText();
            } elseif ($extension === 'docx') {
                $phpWord = IOFactory::load($path);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . ' ';
                        }
                    }
                }
                return trim($text);
            }
            return '';
        } catch (\Exception $e) {
            Log::error('Text extraction failed:', ['error' => $e->getMessage(), 'file' => $file->getClientOriginalName()]);
            return '';
        }
    }

    private function calculateMatchPercentage($text)
    {
        $requirements = ['project', 'management', 'development', 'team', 'strategy'];
        $text = strtolower($text);
        $matched_keywords = [];

        foreach ($requirements as $keyword) {
            if (stripos($text, $keyword) !== false) {
                $matched_keywords[] = $keyword;
            }
        }

        $percentage = count($requirements) > 0 ? (count($matched_keywords) / count($requirements)) * 100 : 0;
        $group = $this->assignGroup($percentage);

        return [
            'percentage' => $percentage,
            'group' => $group,
            'matched_keywords' => $matched_keywords,
        ];
    }

    private function assignGroup($percentage)
    {
        if ($percentage >= 80) {
            return 'Group A';
        } elseif ($percentage >= 50) {
            return 'Group B';
        } elseif ($percentage >= 30) {
            return 'Group C';
        } else {
            return 'Group D';
        }
    }
}