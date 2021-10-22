<?php

it('has posts page')->get('/notas')->assertStatus(200);
it('has create posts page')->get('/notas/create')->assertStatus(200);

it('has show posts page', function () {
    $model = Post::factory()->create();
    $this->get(/$model->path())->assertStatus(200);
});

it('has edit posts page', function () {
    $model = Post::factory()->create();
    $this->get("/$model->path()/edit")->assertStatus(200);
});

it('can edit an single row from posts', function () {

    $model = Post::factory()->create();

    livewire('livewire.backend.post-livewire')
        ->call('edit', $model->id)
        ->set('editing.title', 'foo')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertEquals('foo', Post::first()->title);
});

it('can show an single row from posts', function () {

    $model = Post::factory()->create();

    livewire('livewire.backend.post-livewire')
        ->call('show', $model->id)
        ->assertSee($model->title)
        ->assertHasNoErrors();
});


it('title is required in posts table', function () {
    $model = Post::factory()->create();

    Livewire::test('livewire.backend.post-livewire')
        ->call('edit', $model->id)
        ->set('editing.title', '')
        ->call('save')
        ->assertHasErrors(['editing.title' => 'required']);
}

it('slug is required in posts table', function () {
    $model = Post::factory()->create();

    Livewire::test('livewire.backend.post-livewire')
        ->call('edit', $model->id)
        ->set('editing.slug', '')
        ->call('save')
        ->assertHasErrors(['editing.slug' => 'required']);
}

it('body is required in posts table', function () {
    $model = Post::factory()->create();

    Livewire::test('livewire.backend.post-livewire')
        ->call('edit', $model->id)
        ->set('editing.body', '')
        ->call('save')
        ->assertHasErrors(['editing.body' => 'required']);
}



it('edit slug from title in posts table', function () {
    $model = Post::factory(['title' => 'bar'])->create();
    $newValue = 'foo foo';

    Livewire::test('livewire.backend.post-livewire')
        ->call('edit', $model->id)
        ->set('editing.title', $newValue)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertEquals(Str::slug($newValue), Post::first()->slug);
}
