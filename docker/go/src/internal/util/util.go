package util

import (
	"encoding/json"
	"fmt"
)

func ParseJSONData(data any, v interface{}) error {
	dataBytes, err := json.Marshal(data)
	if err != nil {
		return fmt.Errorf("cannot marshal Data to JSON: %w", err)
	}

	err = json.Unmarshal(dataBytes, v)
	if err != nil {
		return fmt.Errorf("cannot unmarshal Data: %w", err)
	}

	return nil
}
