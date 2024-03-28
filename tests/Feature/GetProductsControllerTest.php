<?php

namespace Tests\Feature;


use Tests\TestCase;

class GetProductsControllerTest extends TestCase
{
    protected array $ogData = [
        "pages" => 3,
        [
            "image" => "https://img.tmstor.es//28132.jpg",
            "id" => 28132,
            "artist" => "The Tests",
            "title" => "Test The Theory",
            "description" => "<span>Following on from the success of the last Album, The Test's are back with a new explosive sound described by MNE as 'Truly Original'.</span><br /><br /><span>The Test's themselves have expressed that this album is the best album they've produced in years.&nbsp;</span><br /><br /><span>Including several exclusive tracks available only on the album with guest features by greats like Jax and legendary guitarist Johnny Saturno this album is set to impress. - See more at: http://test.tmstore.co.uk/#sthash.SagiDcqV.dpuf</span>",
            "price" => "7.00",
            "format" => "download",
            "release_date" => "2016-03-01",
        ],
    ];

    public function test_products_is_successful_response(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    public function test_products_matches_old_signature(): void
    {
        $response = $this->get('/api/products');
        $responseData = $response->json();
        $this->assertEquals(array_keys($responseData[0]), array_keys($this->ogData[0]));

    }

    public function test_products_matches_sections_signature(): void
    {

        // I should really iterate all of them here the original functionality had partials that should be thought about and tested as well
        $response = $this->get('/api/products/hoodies');
        $responseData = $response->json();
        $this->assertEquals(array_keys($responseData[0]), array_keys($this->ogData[0]));

    }

    /**
     * @todo cheque this sorting algorithm actually is meant to work
     */
    public function test_products_matches_old_order_by(): void
    {
        $sorts = [
            "az",
            "za",
            "low",
            "high",
            "old",
            "new",
        ];
        collect($sorts)->each(function ($sort) {
            $response = $this->get("/api/products?sort=$sort");
            $responseData = $response->json();
            $this->assertEquals(array_keys($responseData[0]), array_keys($this->ogData[0]));
        });

    }
}
