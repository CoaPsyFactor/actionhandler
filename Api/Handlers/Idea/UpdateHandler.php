<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/23/17
 * Time: 4:03 PM
 */

namespace Api\Handlers\Idea;


use Api\Middlewares\AuthenticateMiddleware;
use Api\Models\Idea;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\DataTransformer\Transformers\ModelTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Middleware\IMiddleware;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class UpdateHandler implements IApplicationActionHandler, IApplicationActionMiddleware, IApplicationActionValidator
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {

        /** @var Idea $idea */
        $idea = Middleware::getSharedInstance()->get('idea');

        $data = $this->_getUpdateData($idea, $request);

        if (empty($data)) {

            $response->status(IResponseStatus::OK)->addData('message', 'Nothing to update');

            return;
        }

        $idea->setAttributes($data);

        if ($idea->getAttribute('id', IntTransformer::getSharedInstance()) === $idea->save()) {

            $response->data(['message', 'Idea successfully updated', 'idea' => $idea->toArray()]);

            return;
        }

        $response->status(IResponseStatus::INTERNAL_ERROR)->addError('update', 'Failed to update idea');
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware
    {

        return $middleware
            ->add(new AuthenticateMiddleware())
            ->add(new class implements IMiddleware
            {

                public function run(Request $request, Response $response, Middleware $middleware): void
                {

                    /** @var Idea $idea */
                    $idea = $request->parameter('id', null, ModelTransformer::getNewInstance(Idea::class));

                    $creatorId = $idea->user()->getAttribute('id', IntTransformer::getSharedInstance());
                    $tokenUserId = $request->token()->user()->getAttribute('id', IntTransformer::getSharedInstance());

                    if ($creatorId === $tokenUserId) {

                        $middleware->put('idea', $idea);

                        $middleware->next();

                        return;
                    }

                    $response
                        ->status(IResponseStatus::FORBIDDEN)
                        ->addError('owner', 'You are not owner of this idea.');

                    return;
                }
            });
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        $validator->validate([
            'id' => 'required|exists:ideas,id',
            'description' => 'min:' . Idea::MIN_DESCRIPTION_LENGTH . '|max:' . Idea::MAX_DESCRIPTION_LENGTH,
            'idea_category' => 'exists:idea_categories,id'
        ]);

        return $validator;
    }

    private function _getUpdateData(Idea $idea, Request $request): array
    {

        $data = [];

        $ideaCategoryId = $idea->getAttribute('idea_category', IntTransformer::getSharedInstance());

        $data['idea_category'] = $request->data('idea_category', $ideaCategoryId, IntTransformer::getSharedInstance());

        if ($data['idea_category'] === $ideaCategoryId) {

            unset($data['idea_category']);
        }

        $data['description'] = $request->data('description', $idea->getAttribute('description'));

        if ($idea->getAttribute('description') === $data['description']) {

            unset($data['description']);
        }

        return $data;
    }
}