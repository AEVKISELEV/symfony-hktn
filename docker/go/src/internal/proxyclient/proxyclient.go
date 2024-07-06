package proxyclient

import (
	"fmt"
	"net/http"
	"net/url"
	"os"

	"golang.org/x/net/proxy"
)

func ProxyAwareHttpClient() (*http.Client, error) {
	// sane default
	var dialer proxy.Dialer
	// eh, I want the type to be proxy.Dialer but assigning proxy.Direct makes the type proxy.direct
	dialer = proxy.Direct
	proxyServer, isSet := os.LookupEnv("HTTP_PROXY")
	if isSet {
		proxyUrl, err := url.Parse(proxyServer)
		if err != nil {
			return nil, fmt.Errorf("cannot create proxy: %w", err)
		}
		dialer, err = proxy.FromURL(proxyUrl, proxy.Direct)
	}

	// setup a http client
	httpTransport := &http.Transport{}
	httpClient := &http.Client{Transport: httpTransport}
	httpTransport.Dial = dialer.Dial
	return httpClient, nil
}
