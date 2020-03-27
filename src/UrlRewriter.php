<?php
/**
 * Class to rewrite URLs from local to CDN.
 */

namespace MOJDigital\RewriteMediaToS3;

class UrlRewriter
{
    /**
     * Base URL for local uploads directory.
     * Example: http://example.com/wp-content/uploads
     *
     * @var string
     */
    private $localBase = null;

    /**
     * Base URL for the uploads directory on the CDN.
     * Example: https://s3-eu-west-1.amazonaws.com/example/uploads
     *
     * @var string
     */
    private $cdnBase = null;

    /**
     * Class object used to sign urls
     *
     * @var mixed (class object | null)
     */
    private $signed = null;

    /**
     * UrlRewriter constructor.
     *
     * @param string $localUploadsBaseUrl Base URL for local uploads directory.
     * @param string $cdnUploadsBaseUrl Base URL for the uploads directory on the CDN.
     * @param mixed $signed Class object used to sign urls, or null
     */
    public function __construct($localUploadsBaseUrl, $cdnUploadsBaseUrl, $signed)
    {
        $this->localBase = $localUploadsBaseUrl;
        $this->cdnBase = $cdnUploadsBaseUrl;
        $this->signed = $signed;
    }

    /**
     * Rewrite a local URL to the CDN.
     *
     * @param $url
     *
     * @return string
     */
    public function rewriteUrl($url)
    {
        if (stripos($url, $this->localBase) === 0) {
            $url = substr($url, strlen($this->localBase));

            if ($this->signed) {
                return $this->signed->uri($url);
            }

            $url = $this->cdnBase . $url;
        }

        if ($this->signed) {
            if (stripos($url, $this->cdnBase) === 0) {
                $url = substr($url, strlen($this->cdnBase));
            }

            return $this->signed->uri($url);
        }

        return $url;
    }
}
