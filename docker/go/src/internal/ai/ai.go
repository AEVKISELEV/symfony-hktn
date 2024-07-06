package ai

const (
	TypeText = "TEXT"
)

type CallbackResponse struct {
	ID      string `json:"id"`
	Content string `json:"content"`
}
