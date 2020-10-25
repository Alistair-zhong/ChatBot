<?php

namespace Tests\Feature;

use App\File;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Alexusmai\LaravelFileManager\FileManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Alexusmai\LaravelFileManager\Services\ConfigService\DefaultConfigRepository;

class GoogleDriveTest extends TestCase
{
    use RefreshDatabase;

    private $store;
    private $manager;
    private $path;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->store = Storage::disk(getUploadDisk());
        $this->path = '内部文件夹';
        // $this->manager = new FileManager(app(DefaultConfigRepository::class));
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');
        dump($this->store->listContents('', true));
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function google_drive_ext_can_store_properly()
    {
        $this->withoutExceptionHandling();

        $image = UploadedFile::fake()->image('test.jpeg');
        $pre_count = collect($this->store->listContents())->count();
        $this->store->putFileAs('', $image, $image->getClientOriginalName());
        $aft_count = collect($this->store->listContents())->count();

        $this->assertEquals($pre_count + 1, $aft_count);
        dump($this->store->listContents());
    }


    /**
     * @test
     */
    public function can_get_file_by_name()
    {
        $image = UploadedFile::fake()->image('test.jpeg');
        $this->store->putFileAs('', $image, $image->getClientOriginalName());

        dump($this->store->get('test.jpeg'));
    }

    /**
     * @test
     */
    public function see_a_image_preview_response()
    {
        // dump($this->store->listContents());
        dump($this->manager->preview('google', 'test.jpeg'));
    }

    /**
     * @test
     */
    public function data_can_be_save_through_file_uploaded_event_listener()
    {
        $name = Str::random(10) . ".jpeg";

        $file = UploadedFile::fake()->image($name);

        $response = $this->json('post', route('google.uploads'), [
            config('uploads.input_name')    => $file,
        ])->assertOk();

        dump(File::get());
        $this->assertDatabaseHas('uploads', [
            'filename'  => $name,
            'type'      => 'file',
        ]);

        // dump($response->getContent());
        dump($response->decodeResponseJson());
        $response->assertJson([
            'id'    => 1,
        ]);
    }

    /**
     * @test
     */
    public function see_the_result_of_putFileAs()
    {

        $name = Str::random(10) . ".jpeg";
        $image = UploadedFile::fake()->image($name);
        $res = $this->store->putFileAs($this->path, $image, $name);

        dump($res);
        $this->store->assertExists($this->path . "/" . $name);
        return $res;
    }

    /**
     * @test
     */
    public function fileMeta_can_be_got_by_the_result_of_putFileAs_method()
    {
        $path = $this->see_the_result_of_putFileAs();
        dump($this->store->listContents());
        dd($this->store->getMetadata($path));
    }

    /**
     * @test
     */
    public function small_file_can_be_uploaded()
    {
        $name = Str::random(10) . ".jpeg";
        $image = UploadedFile::fake()->image($name, 100, 100)->size(1000);

        $response = $this->json('post', route('fileupload.uploads'), [
            config('uploads.input_name')    => $image,
            'path'                          => $this->path
        ])->assertOk();

        $this->store->assertExists($this->path . "/" . $name);
    }

    /**
     * @test
     */
    public function over_max_size_file_can_not_be_uploaded()
    {
        $name = Str::random(10) . ".jpeg";
        $image = UploadedFile::fake()->image($name, 100, 100)->size(config('uploads.max_size') + 100);

        $response = $this->json('post', route('fileupload.uploads'), [
            config('uploads.input_name')    => $image,
            'path'                          => $this->path
        ])->assertStatus(500);

        $this->store->assertMissing($this->path . "/" . $name);
    }

    /**
     * @test
     */
    public function has_permission_get_thumbnail_in_google_drive()
    {
        $this->withoutExceptionHandling();
        $path = '/user_upload/upload_4ca203fe-89e3-40fe-a8a6-928f30c17a54/local_upload';

        $image = UploadedFile::fake()->image('test.jpeg');
        $pre_count = collect($this->store->listContents($path))->count();
        $data = $this->store->putFileAs($path, $image, $image->getClientOriginalName());
        $meta = $this->store->getMetadata($data);
        $key = substr($meta['virtual_path'], strrpos($meta['virtual_path'], '/') + 1);

        $aft_count = collect($this->store->listContents($path))->count();

        $this->assertEquals($pre_count + 1, $aft_count);
        dump($this->store->listContents());

        dump($this->store->getAdapter()->getService()->files->get($key, ["supportsTeamDrives" => true, 'fields' => "id,webContentLink,thumbnailLink,name"]));
    }
}
