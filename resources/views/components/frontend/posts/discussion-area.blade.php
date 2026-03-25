@props([
    'post' => collect()
])
@if($post->is_published)
    <div class="container mt-4 mb-2" style="max-width: 720px;">
        <h2 class="h5 fw-bold mb-3">Reacties</h2>

        @php
            $mockComments = [
                [
                    'user' => ['name' => 'Jan de Vries'],
                    'created_at' => '2026-03-24 14:30',
                    'body' => 'Interessant artikel! Ik heb hier veel van geleerd.'
                ],
                [
                    'user' => ['name' => 'Lisa van den Berg'],
                    'created_at' => '2026-03-24 15:10',
                    'body' => 'Goed geschreven, bedankt voor de uitleg!'
                ],
                [
                    'user' => ['name' => 'Ahmed Ali'],
                    'created_at' => '2026-03-24 16:05',
                    'body' => 'Ik mis nog een voorbeeld van waar het fout gaat.'
                ],
            ];
        @endphp

        {{-- Reacties weergeven --}}
        @foreach($mockComments as $comment)
            <div class="mb-3 border rounded p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong>{{ $comment['user']['name'] }}</strong>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($comment['created_at'])->format('d-m-Y H:i') }}</small>
                </div>
                <p class="mb-0">{{ $comment['body'] }}</p>
            </div>
        @endforeach

        {{-- Mock reactieformulier --}}
        <div class="mt-4">
            <form>
                <div class="mb-2">
                    <textarea class="form-control" rows="3" placeholder="Laat een reactie achter..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Plaats reactie</button>
            </form>
        </div>
    </div>
@endif
