<?php
/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */


namespace Tph\Onlinedesign\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Tph\Onlinedesign\Model\CanvasFactory;
Use Tph\Onlinedesign\Model\ResourceModel\Canvas;

/**
 * Class AddData
 *
 * @package Tph\Onlinedesign\Setup\Patch\Data
 */
class AddData implements DataPatchInterface, PatchVersionInterface
{


    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var array design Type
     */
    private $designData = [
        [
            'design_title' => 'A4 Document',
            'design_type' => 'A4Document',
            'status' => 1

        ],
        [
           'design_title' => 'Announcement',
            'design_type' => 'Announcement',
            'status' => 1
        ],
        [
            'design_title' => 'Birthday Card',
            'design_type' => 'BirthdayCard',
            'status' => 1

        ],
        [

            'design_title' => 'Birthday Invitation',
            'design_type' => 'BirthdayInvitation',
            'status' => 1
        ],
        [
           'design_title' => 'Blog Banner',
            'design_type' => 'BlogBanner',
            'status' => 1
        ],

        [
            'design_title' => 'Book Cover',
            'design_type' => 'BookCover',
            'status' => 1

        ],
        [

            'design_title' => 'Bookmark',
            'design_type' => 'Bookmark',
            'status' => 1
        ],
        [
            'design_title' => 'Brochure',
            'design_type' => 'Brochure',
            'status' => 1

        ],
        [

            'design_title' => 'Business Card',
            'design_type' => 'BusinessCard',
            'status' => 1
        ],

        [

            'design_title' => 'Calendar',
            'design_type' => 'Calendar',
            'status' => 1
        ],
        [
            'design_title' => 'Card',
            'design_type' => 'Card',
            'status' => 1

        ],
        [

            'design_title' => 'Certificate',
            'design_type' => 'Certificate',
            'status' => 1
        ],
        [

            'design_title' => 'Desktop Wallpaper',
            'design_type' => 'DesktopWallpaper',
            'status' => 1
        ],
        [

            'design_title' => 'Email Header',
            'design_type' => 'EmailHeader',
            'status' => 1
        ],
        [
            'design_title' => 'Etsy Shop Cover',
            'design_type' => 'EtsyShopCover',
            'status' => 1

        ],
        [

            'design_title' => 'Etsy Shop Icon',
            'design_type' => 'EtsyShopIcon',
            'status' => 1
        ],
        [

            'design_title' => 'Facebook Ad',
            'design_type' => 'FacebookAd',
            'status' => 1
        ],

        [

            'design_title' => 'Facebook App Ad',
            'design_type' => 'FacebookAppAd',
            'status' => 1
        ],
        [
            'design_title' => 'Facebook Cover',
            'design_type' => 'FacebookCover',
            'status' => 1

        ],
        [

            'design_title' => 'Facebook Event Cover',
            'design_type' => 'FacebookEventCover',
            'status' => 1
        ],
        [

            'design_title' => 'Facebook Post',
            'design_type' => 'FacebookPost',
            'status' => 1
        ],
        [

            'design_title' => 'Flyer',
            'design_type' => 'Flyer',
            'status' => 1
        ],
        [

            'design_title' => 'Gift Certificate',
            'design_type' => 'GiftCertificate',
            'status' => 1
        ],
        [

            'design_title' => 'Instagram Post',
            'design_type' => 'InstagramPost',
            'status' => 1
        ],

        [

            'design_title' => 'Instagram Story',
            'design_type' => 'InstagramStory',
            'status' => 1
        ],
        [
            'design_title' => 'Invitation',
            'design_type' => 'Invitation',
            'status' => 1

        ],
        [

            'design_title' => 'Invoice',
            'design_type' => 'Invoice',
            'status' => 1
        ],
        [

            'design_title' => 'Label',
            'design_type' => 'Label',
            'status' => 1
        ],
        [

            'design_title' => 'Flyer',
            'design_type' => 'Flyer',
            'status' => 1
        ],
        [
            'design_title' => 'Large Rectangle Ad',
            'design_type' => 'LargeRectangleAd',
            'status' => 1

        ],
        [

            'design_title' => 'Leaderboard Ad',
            'design_type' => 'LeaderboardAd',
            'status' => 1
        ],
        [

            'design_title' => 'Lesson Plan',
            'design_type' => 'LessonPlan',
            'status' => 1
        ],
        [

            'design_title' => 'Letter',
            'design_type' => 'Letter',
            'status' => 1
        ],
        [

            'design_title' => 'LinkedIn Banner',
            'design_type' => 'LinkedInBanner',
            'status' => 1
        ],
        [

            'design_title' => 'Logo',
            'design_type' => 'Logo',
            'status' => 1
        ],
        [

            'design_title' => 'Magazine Cover',
            'design_type' => 'MagazineCover',
            'status' => 1
        ],

        [

            'design_title' => 'Medium Rectangle Ad',
            'design_type' => 'MediumRectangleAd',
            'status' => 1
        ],

        [

            'design_title' => 'Menu',
            'design_type' => 'Menu',
            'status' => 1
        ],
        [

            'design_title' => 'Mind Map',
            'design_type' => 'MindMap',
            'status' => 1
        ],
        [

            'design_title' => 'Newsletter',
            'design_type' => 'Newsletter',
            'status' => 1
        ],
        [

            'design_title' => 'Photo Collage',
            'design_type' => 'PhotoCollage',
            'status' => 1
        ],

        [

            'design_title' => 'Pinterest Graphic',
            'design_type' => 'PinterestGraphic',
            'status' => 1
        ],

        [

            'design_title' => 'Postcard',
            'design_type' => 'Postcard',
            'status' => 1
        ],

        [

            'design_title' => 'Poster',
            'design_type' => 'Poster',
            'status' => 1
        ],
        [

            'design_title' => 'Presentation (16:9)',
            'design_type' => 'Presentation',
            'status' => 1
        ],
        [

            'design_title' => 'Product Label',
            'design_type' => 'ProductLabel',
            'status' => 1
        ],
        [

            'design_title' => 'Photo Collage',
            'design_type' => 'PhotoCollage',
            'status' => 1
        ],
        [

            'design_title' => 'Recipe Card',
            'design_type' => 'RecipeCard',
            'status' => 1
        ],

        [

            'design_title' => 'Resume',
            'design_type' => 'Resume',
            'status' => 1
        ],
        [

            'design_title' => 'Snapchat Geofilter',
            'design_type' => 'SnapchatGeofilter',
            'status' => 1
        ],
        [

            'design_title' => 'Social Media Post',
            'design_type' => 'SocialMedia',
            'status' => 1
        ],

        [

            'design_title' => 'Ticket',
            'design_type' => 'Ticket',
            'status' => 1
        ],
        [

            'design_title' => 'Tumblr Graphic',
            'design_type' => 'TumblrGraphic',
            'status' => 1
        ],
        [

            'design_title' => 'Twitter Header',
            'design_type' => 'TwitterHeader',
            'status' => 1
        ],
        [

            'design_title' => 'Twitter Post',
            'design_type' => 'TwitterPost',
            'status' => 1
        ],
        [

            'design_title' => 'Wattpad Book Cover',
            'design_type' => 'WattpadBookCover',
            'status' => 1
        ],

        [

            'design_title' => 'Wedding Invitation',
            'design_type' => 'WeddingInvitation',
            'status' => 1
        ],
        [

            'design_title' => 'Wide Skyscraper Ad',
            'design_type' => 'WideSkyscraperAd',
            'status' => 1
        ],
        [

            'design_title' => 'Worksheet',
            'design_type' => 'Worksheet',
            'status' => 1
        ],
        [

            'design_title' => 'Yearbook',
            'design_type' => 'Yearbook',
            'status' => 1
        ],
        [

            'design_title' => 'YouTube Channel Art',
            'design_type' => 'YouTubeChannelArt',
            'status' => 1
        ],
        [

            'design_title' => 'YouTube Thumbnail',
            'design_type' => 'YouTubeThumbnail',
            'status' => 1
        ],
        [

            'design_title' => 'Puzzle',
            'design_type' => 'Puzzle',
            'status' => 1
        ],
        [

            'design_title' => 'Canvas',
            'design_type' => 'Canvas',
            'status' => 1
        ]
    ];

    /**
     *
     * @param CanvasFactory $canvasDetailsFactory
     * @param Canvas $canvasDetailsResource
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        CanvasFactory $canvasDetailsFactory,
        Canvas $canvasDetailsResource,
        ModuleDataSetupInterface $moduleDataSetup
    )
    {
        $this->canvasDetailsFactory = $canvasDetailsFactory;
        $this->canvasDetailsResource = $canvasDetailsResource;
        $this->moduleDataSetup=$moduleDataSetup;
    }

    /**
     * Install data row into tph_design_type table
     */
    public function apply()
    {

        $this->moduleDataSetup->startSetup();

        foreach ($this->designData as $imageData) {
            $contactDTO=$this->canvasDetailsFactory->create();
            $contactDTO->setDesignTitle($imageData['design_title'])->setDesignType($imageData['design_type'])
            ->setStatus($imageData['status']);

            try {
                $this->canvasDetailsResource->save($contactDTO);
            } catch (\Exception $e) {
                null; //do nothing
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @return array
     */

    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }
}
