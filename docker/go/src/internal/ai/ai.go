package ai

const (
	TypeText = "text"
)

type CallbackResponse struct {
	ID      string `json:"id"`
	Content string `json:"content"`
}
