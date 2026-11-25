<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'how long timeline coating install days weeks',
            'a' => 'Most SolaraShield coating projects take 2–5 dry working days after prep, depending on roof size, weather, and cure windows. Roof surveys across Austin and Central Texas are usually scheduled within a week.',
        ],
        [
            'q' => 'cost price estimate budget coating roof',
            'a' => 'Reflective roof coatings in Central Texas typically range from about $2.50–$7.50 per square foot installed, depending on substrate condition, primer needs, silicone vs acrylic, and reinforcement work. We provide fixed proposals after an on-roof survey.',
        ],
        [
            'q' => 'elastomeric acrylic silicone cool roof materials',
            'a' => 'We install acrylic elastomeric cool roofs for dry, well-drained decks and high-solids silicone when ponding water or aging single-ply membranes need restoration. Metal roofs get rust primers plus reflective topcoats matched to panel type.',
        ],
        [
            'q' => 'leak ponding water drain seam flashing repair',
            'a' => 'SolaraShield never coats over active leaks. We clear drains, reinforce seams, rebuild flashings, and document wet areas before finish coats. Ponding that holds water 48+ hours usually points to silicone or drainage corrections.',
        ],
        [
            'q' => 'warranty maintenance clean hail inspection',
            'a' => 'Manufacturer material warranties plus SolaraShield workmanship coverage apply when mil thickness and prep specs are met. Keep drains clear, schedule twice-yearly inspections, and call us after hail for a photo assessment.',
        ],
        [
            'q' => 'energy savings cool roof heat attic ac bill',
            'a' => 'High-reflectance white coatings reduce roof surface temperatures and attic heat gain — many Austin commercial clients see measurable HVAC relief in peak summer. Actual savings depend on insulation, HVAC efficiency, and building use.',
        ],
        [
            'q' => 'schedule appointment quote survey contact',
            'a' => 'Call or text (512) 555-0148, use the contact form, or WhatsApp us. Roof surveys for Austin, Round Rock, Cedar Park, and surrounding Central Texas cities are typically available within 3–5 business days.',
        ],
        [
            'q' => 'tpo epdm metal flat roof tear off replace',
            'a' => 'Many aging TPO, EPDM, modified bitumen, and metal roofs can be restored with coating instead of costly tear-off. If wet insulation or structural issues are found, we recommend repair or replacement options before coating.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a roof coating specialist about: {$prompt}",
            'description' => "Write a compelling product/service description for reflective roof coatings: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a roof coating SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this roof coating operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching SolaraShield Coatings. We apply reflective elastomeric, silicone, and metal roof coating systems across Austin and Central Texas. Ask about timelines, cool-roof savings, leak repairs, pricing, or scheduling — or call (512) 555-0148 for a free roof survey.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the SolaraShield Coatings assistant for an Austin, TX roof coating company. Be concise, practical, and focused on elastomeric/silicone coatings, cool roofs, leak repair, and Central Texas climate. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are SolaraShield Coatings assistant (Austin roof coating specialist). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
