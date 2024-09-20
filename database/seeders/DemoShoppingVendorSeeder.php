<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorType;
use App\Traits\ImageGeneratorTrait;
use Illuminate\Database\Seeder;

class DemoShoppingVendorSeeder extends Seeder
{
    use ImageGeneratorTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $vendorTypeId = VendorType::where('slug', 'commerce')->first()->id;
        //delete all vendors with food type
        // $vendorIds = Vendor::where('vendor_type_id', $vendorTypeId)->pluck('id')->toArray();
        // Product::whereIn('vendor_id', $vendorIds)->delete();
        // Vendor::where('vendor_type_id', $vendorTypeId)->delete();


        //
        //create 8 popular ecommerce vendors: Nike, Adidas, Puma, Reebok, Gucci, Louis Vuitton, Zara, H&M
        $vendorNames = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Gucci', 'Louis Vuitton', 'Zara', 'H&M'];
        //short descriptions
        $vendorDecriptions = [
            //Nike
            "Nike, Inc. is an American multinational corporation that is engaged in the design, development, manufacturing, and worldwide marketing and sales of footwear, apparel, equipment, accessories, and services. The company is headquartered near Beaverton, Oregon, in the Portland metropolitan area. It is the world\'s largest supplier of athletic shoes and apparel and a major manufacturer of sports equipment, with revenue in excess of US$37.4 billion in its fiscal year 2020 (ending May 31, 2020). As of 2020, it employed 76,700 people worldwide. In 2020 the brand alone was valued in excess of $32 billion, making it the most valuable brand among sports businesses.",
            //Adidas
            "Adidas AG (German: [ˈʔadiˌdas]; stylized as adidas since 1949) is a German multinational corporation, founded and headquartered in Herzogenaurach, Germany, that designs and manufactures shoes, clothing and accessories. It is the largest sportswear manufacturer in Europe, and the second largest in the world, after Nike. It is the holding company for the Adidas Group, which consists of the Reebok sportswear company, 8.33% of the German football club Bayern München, and Runtastic, an Austrian fitness technology company. Adidas\' revenue for 2018 was listed at €21.915 billion.",
            //Puma
            "Puma SE, branded as Puma, is a German multinational corporation that designs and manufactures athletic and casual footwear, apparel and accessories, which is headquartered in Herzogenaurach, Bavaria, Germany. Puma is the third largest sportswear manufacturer in the world. The company was founded in 1948 by Rudolf Dassler.",
            //Reebok
            "Reebok is a global athletic footwear and apparel company known for its sports and lifestyle products. The company was founded in 1958 in England by Joseph William Foster, who wanted to create athletic shoes that would enhance athletes' performance. The name \"Reebok\" comes from the Afrikaans word for the grey rhebok, a type of African antelope.",
            //Gucci
            "Gucci is renowned for its iconic double-G logo, horsebit loafers, and its association with luxury and sophistication. The brand has expanded its product offerings to include a wide range of fashion items, including ready-to-wear clothing, handbags, shoes, accessories, and fragrances. Gucci's designs often feature bold patterns, vibrant colors, and innovative details.",
            //Louis Vuitton
            "Louis Vuitton Malletier, commonly known as Louis Vuitton (French pronunciation: ​[lwi vɥitɔ̃]) or shortened to LV, is a French fashion house and luxury goods company founded in 1854 by Louis Vuitton. The label's LV monogram appears on most of its products, ranging from luxury trunks and leather goods to ready-to-wear, shoes, watches, jewelry, accessories, sunglasses and books. Louis Vuitton is one of the world's leading international fashion houses; it sells its products through standalone boutiques, lease departments in high-end department stores, and through the e-commerce section of its website.",
            //Zara
            "Zara SA (Spanish: [ˈθaɾa]) is a Spanish apparel retailer based in Arteixo (A Coruña) in Galicia (Spain). The company specializes in fast fashion, and products include clothing, accessories, shoes, swimwear, beauty, and perfumes. It is the largest company in the Inditex group, the world's largest apparel retailer. Zara as of 2017 manages up to 20 clothing collections a year.",
            //H&M
            "H&M, which stands for Hennes & Mauritz, is a Swedish multinational clothing retail company. It was founded in 1947 by Erling Persson in Västerås, Sweden. H&M is known for offering a wide range of affordable and trendy fashion items for men, women, and children, as well as home goods."
        ];

        //array of best selling products from each vendor, name: description
        $vendorProducts = [
            // nike
            [
                [
                    "name" => "Nike Air Max 270",
                    "description" => "The Nike Air Max 270 is an Air Max sneaker designed by Nike\'s senior footwear designer Dylan Raasch. It was inspired by the Air Max 93 and Air Max 180. It features Nike\'s biggest heel Air unit yet for a super-soft ride that feels as impossible as it looks.",
                ],
                [
                    "name" => "Nike Air Force 1",
                    "description" => "The Nike Air Force 1 was originally made for basketball. It was the first shoe to bring Nike Air technology to the court, but its ingenuity didn’t stop there. Designer Bruce Kilgore incorporated a slanted cut to the high-top upper, increasing mobility without compromising support. Then he ditched the herringbone traction pattern typical of basketball shoes at the time in favor of a circular outsole that helps pivot moves. Together, these design choices created a silhouette that was immediately adopted by athletes and artists alike. Over three decades since its first release, the Air Force 1 remains true to its roots while earning its status as a fashion staple for seasons to come.",
                ],
                [
                    "name" => "Nike Air Max 97",
                    "description" => "The Nike Air Max 97 was first released in 1997. The design of the shoe was inspired by the bullet trains of Japan. The Air Max 97 was Nike\'s first shoe that introduced full-length air. The Air Max 97 also introduced a hidden lacing system. Nike Air Max 97 is a popular favorite and deserves every spot in your sneaker rotation. The Air Max 97 was designed with the runner entirely in mind. The full-length visible air unit provided enough cushioning to comfort high-impact runners and keep casual wearers feeling light on their feet. The AM97 maintained the layered look from the AM95, but offered a sleeker take with its metallic coloring. The 3M stripe wrapping around the shoe gave the sneaker even more flash. With its reflective flair and aggressive design, the AM97 was a hit both on the track and in the streets.",
                ],
                [
                    "name" => "Nike Air Max 90",
                    "description" => "The Nike Air Max 90 is a classic sneaker that has become very popular among sneakerheads. The Air Max 90 was first released in 1990 and was known as the Air Max or the Air Max III until 2000 when Nike reissued these classic Nike running shoes. The Nike Air Max 90 is a classic sneaker that has become very popular among sneakerheads. The Air Max 90 was first released in 1990 and was known as the Air Max or the Air Max III until 2000 when Nike reissued these classic Nike running shoes.",
                ],
            ],
            // adidas
            [
                [
                    "name" => "Adidas Superstar",
                    "description" => "The Adidas Superstar is a low-top basketball shoe that released in 1969. It features a rubber shell toe, three stripes across the upper, and a herringbone-patterned sole. The Adidas Superstar is a low-top basketball shoe that released in 1969. It features a rubber shell toe, three stripes across the upper, and a herringbone-patterned sole.",
                ],
                [
                    "name" => "Adidas Stan Smith",
                    "description" => "The Adidas Stan Smith is a tennis shoe made by Adidas. Stan Smith is an American tennis player, who was active between the end of the 1960s and the beginning of the 1980s. Adidas approached him to endorse the so-called Haillet shoe in 1973. Adidas redesigned and changed the name and branding of the shoe in 1978 after Stan Smith signed with the company. The Adidas Stan Smith is a tennis shoe made by Adidas. Stan Smith is an American tennis player, who was active between the end of the 1960s and the beginning of the 1980s. Adidas approached him to endorse the so-called Haillet shoe in 1973. Adidas redesigned and changed the name and branding of the shoe in 1978 after Stan Smith signed with the company.",
                ],
                [
                    "name" => "Adidas NMD",
                    "description" => "The Adidas NMD is a lifestyle sneaker that pays homage to moments from the adidas collective memory. The NMD references archival adidas shoes like the Micro Pacer, the Rising Star and the Boston Super, letting the past empower the future. NMD shoes feature a BOOST midsole that delivers a durable, shock-resistant, responsive sole. Its upper utilizes adidas Primeknit providing new levels of strength, flexibility and stability. The Adidas NMD is a lifestyle sneaker that pays homage to moments from the adidas collective memory. The NMD references archival adidas shoes like the Micro Pacer, the Rising Star and the Boston Super, letting the past empower the future. NMD shoes feature a BOOST midsole that delivers a durable, shock-resistant, responsive sole. Its upper utilizes adidas Primeknit providing new levels of strength, flexibility and stability.",
                ],
                [
                    "name" => "Adidas Yeezy",
                    "description" => "The Adidas Yeezy is a fashion collaboration between German sportswear brand Adidas and American designer, rapper, entrepreneur and personality Kanye West. The collaboration has become notable for its sneakers, and the Yeezy Boost sneaker line has been considered one of the most influential sneaker brands in the world. While mostly known for its sneakers, the collaboration also produced shirts, jackets, track pants, socks, and women\'s shoes.",
                ],
            ],
            // puma
            [
                [
                    "name" => "Puma Suede",
                    "description" => "The Puma Suede is a low-top sneaker known for its suede upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Suede Classic. The Puma Suede is a low-top sneaker known for its suede upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Suede Classic.",
                ],
                [
                    "name" => "Puma Basket",
                    "description" => "The Puma Basket is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Basket Classic. The Puma Basket is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Basket Classic.",
                ],
                [
                    "name" => "Puma Roma",
                    "description" => "The Puma Roma is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Roma Classic. The Puma Roma is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Roma Classic.",
                ],
                [
                    "name" => "Puma Clyde",
                    "description" => "The Puma Clyde is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Clyde Classic. The Puma Clyde is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Puma Clyde Classic.",
                ],
            ],
            // reebok
            [
                [
                    "name" => "Reebok Classic Leather",
                    "description" => "The Reebok Classic Leather is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Classic Leather Classic. The Reebok Classic Leather is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Classic Leather Classic.",
                ],
                [
                    "name" => "Reebok Club C",
                    "description" => "The Reebok Club C is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Club C Classic. The Reebok Club C is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Club C Classic.",
                ],
                [
                    "name" => "Reebok Workout",
                    "description" => "The Reebok Workout is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Workout Classic. The Reebok Workout is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Workout Classic.",
                ],
                [
                    "name" => "Reebok Freestyle",
                    "description" => "The Reebok Freestyle is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Freestyle Classic. The Reebok Freestyle is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Reebok Freestyle Classic.",
                ],
            ],
            // gucci
            [
                [
                    "name" => "Gucci Ace",
                    "description" => "The Gucci Ace is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Ace Classic. The Gucci Ace is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Ace Classic.",
                ],
                [
                    "name" => "Gucci Rhyton",
                    "description" => "The Gucci Rhyton is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Rhyton Classic. The Gucci Rhyton is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Rhyton Classic.",
                ],
                [
                    "name" => "Gucci Screener",
                    "description" => "The Gucci Screener is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Screener Classic. The Gucci Screener is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Screener Classic.",
                ],
                [
                    "name" => "Gucci Tennis 1977",
                    "description" => "The Gucci Tennis 1977 is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Tennis 1977 Classic. The Gucci Tennis 1977 is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Gucci Tennis 1977 Classic.",
                ],
            ],
            // louis vuitton
            [
                [
                    "name" => "Louis Vuitton Archlight",
                    "description" => "The Louis Vuitton Archlight is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton Archlight Classic. The Louis Vuitton Archlight is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton Archlight Classic.",
                ],
                [
                    "name" => "Louis Vuitton Run Away",
                    "description" => "The Louis Vuitton Run Away is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton Run Away Classic. The Louis Vuitton Run Away is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton Run Away Classic.",
                ],
                [
                    "name" => "Louis Vuitton LV Trainer",
                    "description" => "The Louis Vuitton LV Trainer is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton LV Trainer Classic. The Louis Vuitton LV Trainer is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton LV Trainer Classic.",
                ],
                [
                    "name" => "Louis Vuitton LV Archlight",
                    "description" => "The Louis Vuitton LV Archlight is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton LV Archlight Classic. The Louis Vuitton LV Archlight is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Louis Vuitton LV Archlight Classic.",
                ],
            ],
            // zara
            [
                [
                    "name" => "Zara Leather Sneakers",
                    "description" => "The Zara Leather Sneakers is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Zara Leather Sneakers Classic. The Zara Leather Sneakers is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the Zara Leather Sneakers Classic.",
                ],
            ],
            // h&m
            [
                [
                    "name" => "H&M Leather Sneakers",
                    "description" => "The H&M Leather Sneakers is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the H&M Leather Sneakers Classic. The H&M Leather Sneakers is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the H&M Leather Sneakers Classic.",
                ],
                //hm bags
                [
                    "name" => "H&M Leather Bag",
                    "description" => "The H&M Leather Bag is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the H&M Leather Bag Classic. The H&M Leather Bag is a low-top sneaker known for its leather upper and thick rubber sole. It was introduced in 1968 and is often referred to as the H&M Leather Bag Classic.",
                ],
            ],
        ];
        //
        $faker = \Faker\Factory::create();
        //Loop through the vendor names
        foreach ($vendorNames as $key => $vendorName) {
            $model = new Vendor();
            $model->name = $vendorName;
            $model->description = $vendorDecriptions[$key];
            $model->delivery_fee = $faker->randomNumber(2, false);
            $model->delivery_range = $faker->randomNumber(3, false);
            $model->tax = $faker->randomNumber(2, false);
            $model->phone = $faker->phoneNumber;
            $model->email = $faker->email;
            $model->address = $faker->address;
            $model->latitude = $faker->latitude();
            $model->longitude = $faker->longitude();
            $model->tax = rand(0, 1);
            $model->pickup = rand(0, 1);
            $model->delivery = rand(0, 1);
            $model->is_active = 1;
            $model->vendor_type_id = $vendorTypeId;
            $model->saveQuietly();
            //logo gen
            try {
                $imageUrl = $this->generateImage($vendorName, "business,logo");
                $model->clearMediaCollection();
                $model->addMediaFromUrl($imageUrl)
                    ->usingFileName(genFileName($imageUrl))
                    ->toMediaCollection("logo");
                $featureImageUrl = $this->generateImage($vendorName, "banner,advert", "landscape");
                $model->addMediaFromUrl($featureImageUrl)
                    ->usingFileName(genFileName($featureImageUrl))
                    ->toMediaCollection("feature_image");
            } catch (\Exception $ex) {
                logger("Error", [$ex->getMessage()]);
            }

            //add product
            $vendorProductList = $vendorProducts[$key];
            foreach ($vendorProductList as $vendorProductData) {
                $product = new Product();
                $product->name = $vendorProductData['name'];
                $product->description = $vendorProductData['description'];
                $product->price = $faker->randomNumber(4, false);
                $product->is_active = 1;
                $product->deliverable = rand(0, 1);
                $product->featured = rand(0, 1);
                $product->vendor_id = $model->id;
                $product->saveQuietly();
                //
                try {
                    $imageUrl = $this->generateImage($product->name, "Shopping,commerce");
                    $product->addMediaFromUrl($imageUrl)
                        ->usingFileName(genFileName($imageUrl))
                        ->toMediaCollection();
                } catch (\Exception $ex) {
                    logger("Error", [$ex->getMessage()]);
                }
            }
        }
    }
}