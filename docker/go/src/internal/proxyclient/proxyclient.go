package proxyclient

import (
	"fmt"
	"net/http"
	"net/url"
	"os"

	"golang.org/x/net/proxy"
)

func ProxyAwareHttpClient(trans bool) (*http.Client, error) {
	// sane default
	var dialer proxy.Dialer
	// eh, I want the type to be proxy.Dialer but assigning proxy.Direct makes the type proxy.direct
	dialer = proxy.Direct
	proxyServer, isSet := os.LookupEnv("HTTP_PROXY")
	if isSet && !trans {
		proxyUrl, err := url.Parse(proxyServer)
		if err != nil {
			return nil, fmt.Errorf("cannot parse url")
		}
		return &http.Client{Transport: &http.Transport{Proxy: http.ProxyURL(proxyUrl)}}, nil
	}

	// setup a http client
	httpTransport := &http.Transport{}
	httpClient := &http.Client{Transport: httpTransport}
	httpTransport.Dial = dialer.Dial
	return httpClient, nil
}
